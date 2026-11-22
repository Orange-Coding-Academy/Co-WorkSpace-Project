<?php


namespace Database\Seeders;

use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WorkspaceImageSeeder extends Seeder
{
 public function run(): void
    {
        $images = [
            'images/nexora/workspace-lounge-01.jpg',
            'images/nexora/workspace-meeting-02.jpg',
            'images/nexora/workspace-open-office-03.jpg',
            'images/nexora/workspace-lounge-04.jpg',
        ];

        $workspaces = Workspace::query()->orderBy('id')->get();

        if ($workspaces->isEmpty()) {
            $this->command?->warn('No workspaces were found. No image records were added.');
            return;
        }

        DB::transaction(function () use ($workspaces, $images) {
            foreach ($workspaces as $index => $workspace) {
                $path = $images[$index % count($images)];

                // Make sure the new image becomes the primary image.
                $workspace->images()->update(['is_primary' => false]);

                // Reuse an existing image row instead of creating duplicates
                // every time this seeder is run.
                $image = $workspace->images()->orderBy('id')->first();

                $data = [
                    'image_path' => $path,
                    'alt_text' => $workspace->name . ' workspace',
                    'is_primary' => true,
                    'sort_order' => 0,
                ];

                if ($image) {
                    $image->update($data);
                } else {
                    $workspace->images()->create($data);
                }
            }
        });

        $this->command?->info('Primary workspace images updated for ' . $workspaces->count() . ' workspaces.');
    }
}
