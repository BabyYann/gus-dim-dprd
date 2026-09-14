<?php

namespace App\Events;

use App\Models\Pendukung;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendukungStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pendukung $pendukung,
        public string $oldStatus,
        public string $newStatus,
        public string $actorName
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('gusdim-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PendukungStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pendukung->id,
            'nama' => $this->pendukung->nama,
            'jalur' => $this->pendukung->jalur,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'actor_name' => $this->actorName,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
