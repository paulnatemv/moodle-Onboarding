<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_onboarding;

/**
 * Flow entity class.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flow {

    /** @var int|null The flow ID. */
    protected $id;

    /** @var string The flow name. */
    protected $name;

    /** @var string The flow description. */
    protected $description;

    /** @var bool Whether the flow is enabled. */
    protected $enabled;

    /** @var bool Whether the flow is mandatory. */
    protected $mandatory;

    /** @var array|null Target role IDs. */
    protected $targetroles;

    /** @var string|null Redirect URL after completion. */
    protected $redirecturl;

    /** @var int Sort order. */
    protected $sortorder;

    /** @var int Time created. */
    protected $timecreated;

    /** @var int Time modified. */
    protected $timemodified;

    /** @var int User who modified. */
    protected $usermodified;

    /** @var array Cached steps. */
    protected $steps = null;

    /**
     * Create a flow instance from an ID.
     *
     * @param int $id The flow ID.
     * @return self
     * @throws \dml_exception If flow not found.
     */
    public static function instance(int $id): self {
        global $DB;

        $record = $DB->get_record('local_onboarding_flows', ['id' => $id], '*', MUST_EXIST);
        return self::from_record($record);
    }

    /**
     * Create a flow instance from a database record.
     *
     * @param \stdClass $record The database record.
     * @return self
     */
    public static function from_record(\stdClass $record): self {
        $flow = new self();
        $flow->id = $record->id;
        $flow->name = $record->name;
        $flow->description = $record->description ?? '';
        $flow->enabled = (bool) $record->enabled;
        $flow->mandatory = (bool) $record->mandatory;
        $flow->targetroles = !empty($record->targetroles) ? json_decode($record->targetroles, true) : null;
        $flow->redirecturl = $record->redirecturl ?? null;
        $flow->sortorder = (int) $record->sortorder;
        $flow->timecreated = (int) $record->timecreated;
        $flow->timemodified = (int) $record->timemodified;
        $flow->usermodified = (int) $record->usermodified;

        return $flow;
    }

    /**
     * Get all flows.
     *
     * @param bool $enabledonly Only return enabled flows.
     * @return array Array of flow objects.
     */
    public static function get_all(bool $enabledonly = false): array {
        global $DB;

        $conditions = $enabledonly ? ['enabled' => 1] : [];
        $records = $DB->get_records('local_onboarding_flows', $conditions, 'sortorder ASC');

        $flows = [];
        foreach ($records as $record) {
            $flows[] = self::from_record($record);
        }

        return $flows;
    }

    /**
     * Create a new flow.
     *
     * @return self
     */
    public static function create(): self {
        $flow = new self();
        $flow->enabled = false;
        $flow->mandatory = true;
        $flow->sortorder = 0;

        return $flow;
    }

    /**
     * Get the flow ID.
     *
     * @return int|null
     */
    public function get_id(): ?int {
        return $this->id;
    }

    /**
     * Get the flow name.
     *
     * @return string
     */
    public function get_name(): string {
        return $this->name ?? '';
    }

    /**
     * Set the flow name.
     *
     * @param string $name
     * @return self
     */
    public function set_name(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the flow description.
     *
     * @return string
     */
    public function get_description(): string {
        return $this->description ?? '';
    }

    /**
     * Set the flow description.
     *
     * @param string $description
     * @return self
     */
    public function set_description(string $description): self {
        $this->description = $description;
        return $this;
    }

    /**
     * Check if flow is enabled.
     *
     * @return bool
     */
    public function is_enabled(): bool {
        return $this->enabled;
    }

    /**
     * Set enabled status.
     *
     * @param bool $enabled
     * @return self
     */
    public function set_enabled(bool $enabled): self {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Check if flow is mandatory.
     *
     * @return bool
     */
    public function is_mandatory(): bool {
        return $this->mandatory;
    }

    /**
     * Set mandatory status.
     *
     * @param bool $mandatory
     * @return self
     */
    public function set_mandatory(bool $mandatory): self {
        $this->mandatory = $mandatory;
        return $this;
    }

    /**
     * Get target roles.
     *
     * @return array|null
     */
    public function get_target_roles(): ?array {
        return $this->targetroles;
    }

    /**
     * Set target roles.
     *
     * @param array|null $roles
     * @return self
     */
    public function set_target_roles(?array $roles): self {
        $this->targetroles = $roles;
        return $this;
    }

    /**
     * Get redirect URL.
     *
     * @return string|null
     */
    public function get_redirect_url(): ?string {
        return $this->redirecturl;
    }

    /**
     * Set redirect URL.
     *
     * @param string|null $url
     * @return self
     */
    public function set_redirect_url(?string $url): self {
        $this->redirecturl = $url;
        return $this;
    }

    /**
     * Get sort order.
     *
     * @return int
     */
    public function get_sortorder(): int {
        return $this->sortorder;
    }

    /**
     * Set sort order.
     *
     * @param int $sortorder
     * @return self
     */
    public function set_sortorder(int $sortorder): self {
        $this->sortorder = $sortorder;
        return $this;
    }

    /**
     * Get steps for this flow.
     *
     * @return array Array of step objects.
     */
    public function get_steps(): array {
        if ($this->steps === null && $this->id) {
            $this->steps = step::get_for_flow($this->id);
        }
        return $this->steps ?? [];
    }

    /**
     * Count steps in this flow.
     *
     * @return int
     */
    public function count_steps(): int {
        return count($this->get_steps());
    }

    /**
     * Get the first step.
     *
     * @return step|null
     */
    public function get_first_step(): ?step {
        $steps = $this->get_steps();
        return !empty($steps) ? reset($steps) : null;
    }

    /**
     * Get the next step after the given step ID.
     *
     * @param int $currentstepid The current step ID.
     * @return step|null
     */
    public function get_next_step(int $currentstepid): ?step {
        $steps = $this->get_steps();
        $found = false;

        foreach ($steps as $step) {
            if ($found) {
                return $step;
            }
            if ($step->get_id() === $currentstepid) {
                $found = true;
            }
        }

        return null;
    }

    /**
     * Convert to database record.
     *
     * @return \stdClass
     */
    public function to_record(): \stdClass {
        $record = new \stdClass();

        if ($this->id) {
            $record->id = $this->id;
        }

        $record->name = $this->name;
        $record->description = $this->description ?? '';
        $record->enabled = $this->enabled ? 1 : 0;
        $record->mandatory = $this->mandatory ? 1 : 0;
        $record->targetroles = $this->targetroles ? json_encode($this->targetroles) : null;
        $record->redirecturl = $this->redirecturl;
        $record->sortorder = $this->sortorder;
        $record->timemodified = time();

        if (!$this->id) {
            $record->timecreated = time();
        }

        global $USER;
        $record->usermodified = $USER->id;

        return $record;
    }

    /**
     * Save the flow to database.
     *
     * @return self
     */
    public function save(): self {
        global $DB;

        $record = $this->to_record();

        if ($this->id) {
            $DB->update_record('local_onboarding_flows', $record);
        } else {
            $this->id = $DB->insert_record('local_onboarding_flows', $record);
        }

        return $this;
    }

    /**
     * Delete the flow.
     *
     * @return void
     */
    public function delete(): void {
        global $DB;

        if (!$this->id) {
            return;
        }

        // Delete all steps first.
        $DB->delete_records('local_onboarding_steps', ['flowid' => $this->id]);

        // Delete all completion records.
        $DB->delete_records('local_onboarding_completion', ['flowid' => $this->id]);

        // Delete the flow.
        $DB->delete_records('local_onboarding_flows', ['id' => $this->id]);
    }
}
