<?php

namespace App\Services;

use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\String_;

class WorkoutSessionService
{
    private static ?WorkoutSessionService $instance = null;
    private const SESSION_KEY = 'active_workout_session';

    private int $userId;
    private int $workoutId;
    private string $createdAt;
    private ?string $pausedAt = null;
    private bool $isPaused = false;
    private ?string $endAt = null;
    private ?string $duration = null;

    private ?int $totalPausedSeconds = 0;

    private bool $isProgram = false;

    
    private function __construct(int $workoutId)
    {
        $this->userId    = Auth::id();
        $this->workoutId = $workoutId;
        $this->createdAt = now()->toDateTimeString();
    }

    public static function getInstance(int $workoutId = null): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        if (Session::has(self::SESSION_KEY)) {
            $data = Session::get(self::SESSION_KEY);

            $service = new self($data['workoutId']);
            $service->userId    = $data['userId'];
            $service->createdAt = $data['createdAt'];
            $service->pausedAt  = $data['pausedAt'];
            $service->isPaused  = $data['isPaused'];
            $service->endAt     = $data['endAt'];
            $service->totalPausedSeconds = $data['totalPausedSeconds'] ?? 0;
            $service->isProgram = $data['isProgram'];

            return self::$instance = $service;
        }

        if ($workoutId === null) {
            throw new \Exception("No active session exists and no workoutId provided.");
        }

        $service = new self($workoutId);
        $service->persist();
        return self::$instance = $service;
    }

    
    public static function hasActiveSession(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    
    public function pause(): void
    {
        if ($this->isPaused) {
            throw new \Exception("Workout is already paused.");
        }

        $this->isPaused = true;
        $this->pausedAt = time(); 
        $this->persist();
    }

    
    public function resume(): void
    {
        if (!$this->isPaused) {
            throw new \Exception("Workout is not paused.");
        }

        $this->isPaused = false;

        if ($this->pausedAt) {
            $this->totalPausedSeconds += time() - $this->pausedAt;
        }

        $this->pausedAt = null;
        $this->persist();
    }

    public function setDuration(String $duration): void
    {
        $this->duration = $duration;
    }

    public function setisProgram() {
        $this->isProgram = true;
        $this->persist();
    }

    public function getisProgram() {
        return $this->isProgram;
    }

    public function finish(): WorkoutSession
    {
        $this->endAt = now()->toDateTimeString();
        $this->isPaused = false;

        // Persist final state into DB
        $session = WorkoutSession::create([
            'user_id'    => $this->userId,
            'workout_id' => $this->workoutId,
            'start_time' => $this->createdAt,
            'paused_at'  => $this->pausedAt,
            'end_time'   => $this->endAt,
            'is_paused'  => $this->isPaused,
            'duration'   => $this->duration,
        ]);

        Session::forget(self::SESSION_KEY);
        self::$instance = null;

        return $session;
    }

    public function discard(): int
    {
        Session::forget(self::SESSION_KEY);

        return $this->workoutId;
    }

    public function isPaused(): bool
    {
        return $this->isPaused;
    }

    public function getDuration(): int
    {
        $startTimestamp = strtotime($this->createdAt);

        if ($this->isPaused && $this->pausedAt) {
            return $this->pausedAt - $startTimestamp - $this->totalPausedSeconds;
        }

        return time() - $startTimestamp - $this->totalPausedSeconds;
    }

    public function getID(): int
    {
        return $this->workoutId;
    }

    private function persist(): void
    {
        Session::put(self::SESSION_KEY, [
            'userId' => $this->userId,
            'workoutId' => $this->workoutId,
            'createdAt' => $this->createdAt,
            'pausedAt' => $this->pausedAt,
            'isPaused' => $this->isPaused,
            'endAt' => $this->endAt,
            'totalPausedSeconds' => $this->totalPausedSeconds, 
            'isProgram' => $this->isProgram
        ]);
    }
}
