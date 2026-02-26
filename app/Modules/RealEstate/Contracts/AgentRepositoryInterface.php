<?php

namespace App\Modules\RealEstate\Contracts;

use App\Modules\RealEstate\Models\Agent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AgentRepositoryInterface
{
    /**
     * Create a new agent.
     *
     * @param array $data
     * @return Agent
     */
    public function create(array $data): Agent;

    /**
     * Update an agent.
     *
     * @param Agent $agent
     * @param array $data
     * @return Agent
     */
    public function update(Agent $agent, array $data): Agent;

    /**
     * Delete an agent.
     *
     * @param Agent $agent
     * @return bool
     */
    public function delete(Agent $agent): bool;

    /**
     * Find agent by ID.
     *
     * @param int $id
     * @return Agent|null
     */
    public function findById(int $id): ?Agent;

    /**
     * Find agent by slug.
     *
     * @param string $slug
     * @return Agent|null
     */
    public function findBySlug(string $slug): ?Agent;

    /**
     * Find approved agent by slug.
     *
     * @param string $slug
     * @return Agent|null
     */
    public function findApprovedBySlug(string $slug): ?Agent;

    /**
     * Find agent by user ID.
     *
     * @param int $userId
     * @return Agent|null
     */
    public function findByUserId(int $userId): ?Agent;

    /**
     * Get all agents with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all approved agents with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateApproved(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all approved agents.
     *
     * @return Collection
     */
    public function findApproved(): Collection;

    /**
     * Get featured agents.
     *
     * @param int $limit
     * @return Collection
     */
    public function findFeatured(int $limit = 6): Collection;

    /**
     * Search agents by name.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get agents by city.
     *
     * @param string $city
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByCity(string $city, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get agents by specialization.
     *
     * @param string $specialization
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findBySpecialization(string $specialization, int $perPage = 15): LengthAwarePaginator;

    /**
     * Update agent stats.
     *
     * @param int $agentId
     * @param array $stats
     * @return bool
     */
    public function updateStats(int $agentId, array $stats): bool;

    /**
     * Increment property count.
     *
     * @param int $agentId
     * @return bool
     */
    public function incrementPropertyCount(int $agentId): bool;

    /**
     * Decrement property count.
     *
     * @param int $agentId
     * @return bool
     */
    public function decrementPropertyCount(int $agentId): bool;
}
