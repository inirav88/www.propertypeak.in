<?php

namespace App\Modules\RealEstate\Services;

use App\Models\User;
use App\Modules\RealEstate\Models\Agent;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Repositories\AgentRepository;
use App\Modules\RealEstate\Repositories\DeveloperRepository;
use App\Modules\RealEstate\Notifications\RegistrationSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RegistrationService
{
    public function __construct(
        private DeveloperRepository $developerRepository,
        private AgentRepository $agentRepository,
    ) {}

    /**
     * Register a new developer.
     *
     * @param array $userData
     * @param array $developerData
     * @return array{user: User, developer: Developer}
     * @throws \Exception
     */
    public function registerDeveloper(array $userData, array $developerData): array
    {
        return DB::transaction(function () use ($userData, $developerData): array {
            // Create user with developer role and pending status
            $user = $this->createUser($userData, User::ROLE_DEVELOPER);

            // Generate unique slug
            $developerData['slug'] = $this->generateUniqueSlug(
                $developerData['company_name'],
                'developer'
            );

            // Set user_id for developer
            $developerData['user_id'] = $user->id;

            // Create developer profile
            $developer = $this->developerRepository->create($developerData);

            // Notify admin about new registration
            $this->notifyAdmin($user, 'developer');

            return [
                'user' => $user,
                'developer' => $developer,
            ];
        });
    }

    /**
     * Register a new agent.
     *
     * @param array $userData
     * @param array $agentData
     * @return array{user: User, agent: Agent}
     * @throws \Exception
     */
    public function registerAgent(array $userData, array $agentData): array
    {
        return DB::transaction(function () use ($userData, $agentData): array {
            // Create user with agent role and pending status
            $user = $this->createUser($userData, User::ROLE_AGENT);

            // Generate unique slug
            $fullName = $agentData['first_name'] . ' ' . $agentData['last_name'];
            $agentData['slug'] = $this->generateUniqueSlug($fullName, 'agent');

            // Set user_id for agent
            $agentData['user_id'] = $user->id;

            // Create agent profile
            $agent = $this->agentRepository->create($agentData);

            // Notify admin about new registration
            $this->notifyAdmin($user, 'agent');

            return [
                'user' => $user,
                'agent' => $agent,
            ];
        });
    }

    /**
     * Create a user with the specified role.
     *
     * @param array $data
     * @param string $role
     * @return User
     */
    private function createUser(array $data, string $role): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = $role;
        $user->status = User::STATUS_PENDING;
        $user->slug = $this->generateUniqueSlug($data['name'], 'user');
        $user->save();

        return $user;
    }

    /**
     * Generate a unique slug for the given name and type.
     *
     * @param string $name
     * @param string $type (user|developer|agent)
     * @return string
     */
    private function generateUniqueSlug(string $name, string $type): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $type)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug exists for the given type.
     *
     * @param string $slug
     * @param string $type
     * @return bool
     */
    private function slugExists(string $slug, string $type): bool
    {
        return match ($type) {
            'user' => User::where('slug', $slug)->exists(),
            'developer' => Developer::where('slug', $slug)->exists(),
            'agent' => Agent::where('slug', $slug)->exists(),
            default => false,
        };
    }

    /**
     * Notify admin about new registration.
     *
     * @param User $user
     * @param string $type
     * @return void
     */
    private function notifyAdmin(User $user, string $type): void
    {
        // Get all admin users
        $admins = User::where('role', User::ROLE_ADMIN)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new RegistrationSubmitted($user, $type));
        }
    }

    /**
     * Check if email is already registered.
     *
     * @param string $email
     * @return bool
     */
    public function isEmailRegistered(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    /**
     * Get registration status for a user.
     *
     * @param User $user
     * @return array
     */
    public function getRegistrationStatus(User $user): array
    {
        return [
            'role' => $user->role,
            'status' => $user->status,
            'is_approved' => $user->isApproved(),
            'is_pending' => $user->isPending(),
            'is_rejected' => $user->isRejected(),
            'approved_at' => $user->approved_at?->toDateTimeString(),
            'registered_at' => $user->created_at->toDateTimeString(),
        ];
    }
}
