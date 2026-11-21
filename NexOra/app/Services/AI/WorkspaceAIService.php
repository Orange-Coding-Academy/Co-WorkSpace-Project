<?php
namespace App\Services\AI;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WorkspaceAIService
{
    /**
     * Find suitable rooms and ask AI to rank them.
     */
    public function recommendRooms(
        int $attendees,
        array $requiredEquipment,
        float $budget
    ): array {

        // Get active rooms from approved and active workspaces.
        $rooms = DB::table('rooms')
            ->join('workspaces', 'rooms.workspace_id', '=', 'workspaces.id')
            ->where('rooms.is_active', true)
            ->where('workspaces.is_active', true)
            ->where('workspaces.approval_status', 'approved')
            ->where('rooms.capacity', '>=', $attendees)
            ->where('rooms.hourly_price', '<=', $budget)
            ->select(
                'rooms.id',
                'rooms.name',
                'rooms.room_type',
                'rooms.capacity',
                'rooms.facilities',
                'rooms.hourly_price',
                'workspaces.name as workspace_name',
                'workspaces.location'
            )
            ->get();

        // Filter rooms by all requested equipment.
        $matchedRooms = $rooms->filter(function ($room) use ($requiredEquipment) {

            $facilities = json_decode($room->facilities ?? '[]', true);

            if (!is_array($facilities)) {
                $facilities = [];
            }

            $facilities = array_map(
                fn ($item) => strtolower(trim((string) $item)),
                $facilities
            );

            foreach ($requiredEquipment as $equipment) {
                if (!in_array(strtolower(trim($equipment)), $facilities, true)) {
                    return false;
                }
            }

            return true;
        })->values();

        // Convert database results into a clean array.
        $roomData = $matchedRooms->map(function ($room) {

            $facilities = json_decode($room->facilities ?? '[]', true);
return [
    'id' => $room->id,
    'room_name' => $room->name,
    'workspace_name' => $room->workspace_name,
    'location' => $room->location,
    'room_type' => $room->room_type,
    'capacity' => $room->capacity,
    'hourly_price' => (float) $room->hourly_price,
    'facilities' => is_array($facilities) ? $facilities : [],
];

        })->all();

        // No need to call OpenAI if no rooms match.
        if (empty($roomData)) {
            return [
                'answer' => 'No rooms match all your requirements. Try increasing your budget or changing the required equipment.',
                'rooms' => [],
            ];
        }

        // Send only real database results to OpenAI.
        $context = json_encode(
            $roomData,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        $prompt = "
            Member Requirements:
            - Meeting attendees: {$attendees}
            - Required equipment: " . implode(', ', $requiredEquipment) . "
            - Maximum hourly budget: {$budget}

            Available Rooms From Database:
            {$context}

            Your task:
            Recommend the most suitable rooms for this member.

            Rules:
            - Use only the rooms provided above.
            - Never invent room names, prices, locations, or facilities.
            - Consider meeting size, equipment, and budget.
            - Explain why each recommended room is suitable.
            - Prefer rooms with a comfortable capacity, not only the minimum capacity.
            - Do not claim that a room is available for a specific time.
              No booking-time availability was checked.
            - If there are no suitable rooms, explain that clearly.
            - Answer professionally and clearly.
        ";

        return [
            'answer' => $this->askOpenAI($prompt),
            'rooms' => $roomData,
        ];
    }

    /**
     * Send request to OpenAI Responses API.
     */
    private function askOpenAI(string $prompt): string
    {
        $apiKey = config('services.openai.key');
        $model = config('services.openai.model');

        if (!$apiKey) {
            throw new RuntimeException('OpenAI API key is missing.');
        }

        if (!$model) {
            throw new RuntimeException('OpenAI model is missing.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post('https://api.openai.com/v1/responses', [
                'model' => $model,

                'instructions' => '
                    You are NEXORA Workspace AI Assistant.

                    Help members choose suitable coworking rooms.

                    Use only the database information provided.
                    Never invent room information, prices, or facilities.
                    Explain recommendations in a professional and friendly way.
                ',

                'input' => $prompt,
            ]);

        $response->throw();

        $output = $response->json('output', []);

        foreach ($output as $item) {

            if (($item['type'] ?? '') !== 'message') {
                continue;
            }

            foreach ($item['content'] ?? [] as $content) {

                if (($content['type'] ?? '') === 'output_text') {
                    return $content['text'];
                }
            }
        }

        throw new RuntimeException('No text response received from OpenAI.');
    }
}
