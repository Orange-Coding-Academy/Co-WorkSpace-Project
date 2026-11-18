<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class ManagerAIService
{
    public function ask(string $question): string
    {
        $apiKey = config('services.openai.key');

        if (!$apiKey) {
            throw new RuntimeException('OpenAI API key is missing.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model'),
                'instructions' => '
    You are NEXORA AI, a professional assistant for coworking space managers.

    Your responsibilities:
    - Analyze booking data.
    - Identify booking trends and performance.
    - Provide professional business recommendations.
    - Help managers improve their workspace performance.

    Important rules:
    - Use only the database data provided in the prompt.
    - Never invent bookings, revenue, prices, or statistics.
    - Analyze confirmed bookings only.
    - If data is empty, clearly explain that there is not enough data.
    - Do not reveal information belonging to other managers.
    - Answer professionally and clearly.
',
                'input' => $question,
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


public function getManagerOverview(User $manager): array
{
   
    $workspaceIds = DB::table('workspaces')->where('manager_id', $manager->id)->pluck('id');
    $totalWorkspaces = $workspaceIds->count();
    $totalBookings = DB::table('bookings')->join('rooms', 'bookings.room_id', '=', 'rooms.id')->whereIn('rooms.workspace_id', $workspaceIds)->where('bookings.status', 'confirmed')->count('bookings.id');
    // which workspace have most booking 
    $topWorkspace = DB::table('workspaces')->join('rooms', 'workspaces.id', '=', 'rooms.workspace_id')
        ->join('bookings', 'rooms.id', '=', 'bookings.room_id')
        ->where('workspaces.manager_id', $manager->id)->where('bookings.status', 'confirmed')->select('workspaces.name')
        ->selectRaw('COUNT(bookings.id) as bookings_count')
        ->groupBy('workspaces.id', 'workspaces.name')->orderByDesc('bookings_count')->first();

        $busyDays = DB::table('bookings')->join('rooms', 'bookings.room_id', '=', 'rooms.id')->join('workspaces', 'rooms.workspace_id', '=', 'workspaces.id')
    ->where('workspaces.manager_id', $manager->id)->where('bookings.status', 'confirmed')
    ->selectRaw('DAYNAME(bookings.start_date_time) as day_name')->selectRaw('COUNT(bookings.id) as bookings_count')
    ->groupByRaw('DAYNAME(bookings.start_date_time)')->orderByDesc('bookings_count')->get();

    $financialData = DB::table('bookings')
    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')->join('workspaces', 'rooms.workspace_id', '=', 'workspaces.id')
    ->where('workspaces.manager_id', $manager->id)->where('bookings.status', 'confirmed')
    ->selectRaw('COALESCE(SUM(bookings.total_price), 0) as total_booking_value')->selectRaw('COALESCE(SUM(bookings.platform_commission_amount), 0) as total_commission')
    ->selectRaw('COALESCE(SUM(bookings.workspace_net_amount), 0) as workspace_net')->first();

$monthlyBookings = DB::table('bookings')
    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
    ->join('workspaces', 'rooms.workspace_id', '=', 'workspaces.id')
    ->where('workspaces.manager_id', $manager->id)
    ->where('bookings.status', 'confirmed')
    ->where('bookings.start_date_time', '>=', now()->subMonths(6))
    ->selectRaw("DATE_FORMAT(bookings.start_date_time, '%Y-%m') as month")
    ->selectRaw('COUNT(bookings.id) as bookings_count')
    ->groupByRaw("DATE_FORMAT(bookings.start_date_time, '%Y-%m')")
    ->orderBy('month')
    ->get();

  return [
    'total_workspaces' => $totalWorkspaces,

    'total_confirmed_bookings' => $totalBookings,

    'most_booked_workspace' => $topWorkspace
        ? [
            'name' => $topWorkspace->name,
            'bookings_count' => $topWorkspace->bookings_count,
        ]
        : null,

    'busy_days' => $busyDays,

    'financial_data' => [
        'total_booking_value' => $financialData->total_booking_value,
        'total_commission' => $financialData->total_commission,
        'workspace_net' => $financialData->workspace_net,
    ],

    'monthly_bookings' => $monthlyBookings,
];
}

public function askWithManagerData(User $manager, string $question): string
{
    // Get real data belonging to this manager
    $managerData = $this->getManagerOverview($manager);

    // Convert the data into JSON
    $context = json_encode(
        $managerData,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );

    // Send the question together with the database data
    $prompt = "
        Manager Data:
        {$context}

        Manager Question:
        {$question}

        Answer using only the provided database data.
        Do not invent numbers or facts.
        If the data is empty or insufficient, explain that clearly.
    ";

    return $this->ask($prompt);
}

public function getManagerWorkspaces(User $manager): array
{
    return DB::table('workspaces')
    ->where('manager_id', $manager->id)->where('is_active', true)->select('id','name','location','description')->get()->toArray();
}
public function generateMarketingContent(
    User $manager,
    int $workspaceId,
    string $request
): string {

    $workspace = DB::table('workspaces')
        ->where('id', $workspaceId)
        ->where('manager_id', $manager->id)
        ->where('is_active', true)
        ->first();

    if (!$workspace) {
        throw new RuntimeException('Workspace not found.');
    }

    $workspaceData = [
        'name' => $workspace->name,
        'location' => $workspace->location,
        'description' => $workspace->description,
    ];

    $context = json_encode(
        $workspaceData,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );

    $prompt = "
        Workspace Information:
        {$context}

        Marketing Request:
        {$request}

        Write professional marketing content based only on
        the provided workspace information.

        Do not invent prices, facilities, services, or offers.
        If information is missing, do not make assumptions.
    ";

    return $this->ask($prompt);
}
public function getMarketingRecipients(User $manager)
{
    return DB::table('users')
        ->join('bookings', 'bookings.user_id', '=', 'users.id')
        ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
        ->join('workspaces', 'workspaces.id', '=', 'rooms.workspace_id')
        ->where('workspaces.manager_id', $manager->id)
        ->whereIn('bookings.status', ['confirmed', 'completed'])
        ->whereNotNull('users.email')
        ->where('users.email', '!=', '')
        ->select('users.id', 'users.name', 'users.email')
        ->distinct()
        ->get();
}

}