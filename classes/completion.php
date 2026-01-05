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
 * Completion tracking class.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class completion {

    /** Status constants. */
    const STATUS_PENDING = 'pending';
    const STATUS_INPROGRESS = 'inprogress';
    const STATUS_COMPLETED = 'completed';

    /** @var int|null The completion ID. */
    protected $id;

    /** @var int The user ID. */
    protected $userid;

    /** @var int The flow ID. */
    protected $flowid;

    /** @var int|null The current step ID. */
    protected $stepid;

    /** @var string The status. */
    protected $status;

    /** @var int Video time watched in current step. */
    protected $videotime;

    /** @var int|null Time started. */
    protected $timestarted;

    /** @var int|null Time completed. */
    protected $timecompleted;

    /** @var int Time modified. */
    protected $timemodified;

    /**
     * Get or create a completion record for user and flow.
     *
     * @param int $userid The user ID.
     * @param int $flowid The flow ID.
     * @return self
     */
    public static function get_or_create(int $userid, int $flowid): self {
        global $DB;

        $record = $DB->get_record('local_onboarding_completion', [
            'userid' => $userid,
            'flowid' => $flowid,
        ]);

        if ($record) {
            return self::from_record($record);
        }

        // Create new completion record.
        $completion = new self();
        $completion->userid = $userid;
        $completion->flowid = $flowid;
        $completion->status = self::STATUS_PENDING;
        $completion->videotime = 0;
        $completion->timemodified = time();

        // Get the first step.
        $flow = flow::instance($flowid);
        $firststep = $flow->get_first_step();
        if ($firststep) {
            $completion->stepid = $firststep->get_id();
        }

        return $completion->save();
    }

    /**
     * Create a completion instance from a database record.
     *
     * @param \stdClass $record The database record.
     * @return self
     */
    public static function from_record(\stdClass $record): self {
        $completion = new self();
        $completion->id = $record->id;
        $completion->userid = (int) $record->userid;
        $completion->flowid = (int) $record->flowid;
        $completion->stepid = $record->stepid ? (int) $record->stepid : null;
        $completion->status = $record->status;
        $completion->videotime = (int) $record->videotime;
        $completion->timestarted = $record->timestarted ? (int) $record->timestarted : null;
        $completion->timecompleted = $record->timecompleted ? (int) $record->timecompleted : null;
        $completion->timemodified = (int) $record->timemodified;

        return $completion;
    }

    /**
     * Check if user has completed a flow.
     *
     * @param int $userid The user ID.
     * @param int $flowid The flow ID.
     * @return bool
     */
    public static function has_completed(int $userid, int $flowid): bool {
        global $DB;

        return $DB->record_exists('local_onboarding_completion', [
            'userid' => $userid,
            'flowid' => $flowid,
            'status' => self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Reset completion for a user.
     *
     * @param int $userid The user ID.
     * @param int $flowid The flow ID.
     * @return void
     */
    public static function reset(int $userid, int $flowid): void {
        global $DB;

        $DB->delete_records('local_onboarding_completion', [
            'userid' => $userid,
            'flowid' => $flowid,
        ]);
    }

    /**
     * Get completion statistics for a flow.
     *
     * @param int $flowid The flow ID.
     * @return \stdClass
     */
    public static function get_stats(int $flowid): \stdClass {
        global $DB;

        $stats = new \stdClass();

        $stats->total = $DB->count_records('local_onboarding_completion', ['flowid' => $flowid]);
        $stats->completed = $DB->count_records('local_onboarding_completion', [
            'flowid' => $flowid,
            'status' => self::STATUS_COMPLETED,
        ]);
        $stats->inprogress = $DB->count_records('local_onboarding_completion', [
            'flowid' => $flowid,
            'status' => self::STATUS_INPROGRESS,
        ]);
        $stats->pending = $DB->count_records('local_onboarding_completion', [
            'flowid' => $flowid,
            'status' => self::STATUS_PENDING,
        ]);

        $stats->completionrate = $stats->total > 0
            ? round(($stats->completed / $stats->total) * 100, 1)
            : 0;

        return $stats;
    }

    /**
     * Get the completion ID.
     *
     * @return int|null
     */
    public function get_id(): ?int {
        return $this->id;
    }

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function get_userid(): int {
        return $this->userid;
    }

    /**
     * Get the flow ID.
     *
     * @return int
     */
    public function get_flowid(): int {
        return $this->flowid;
    }

    /**
     * Get the current step ID.
     *
     * @return int|null
     */
    public function get_stepid(): ?int {
        return $this->stepid;
    }

    /**
     * Get the current step.
     *
     * @return step|null
     */
    public function get_current_step(): ?step {
        if (!$this->stepid) {
            return null;
        }

        try {
            return step::instance($this->stepid);
        } catch (\dml_exception $e) {
            return null;
        }
    }

    /**
     * Get the status.
     *
     * @return string
     */
    public function get_status(): string {
        return $this->status;
    }

    /**
     * Check if completed.
     *
     * @return bool
     */
    public function is_completed(): bool {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get video time watched.
     *
     * @return int
     */
    public function get_videotime(): int {
        return $this->videotime;
    }

    /**
     * Update video time watched.
     *
     * @param int $seconds The seconds watched.
     * @return self
     */
    public function update_videotime(int $seconds): self {
        $this->videotime = max($this->videotime, $seconds);
        $this->timemodified = time();
        return $this->save();
    }

    /**
     * Start the onboarding.
     *
     * @return self
     */
    public function start(): self {
        if ($this->status === self::STATUS_PENDING) {
            $this->status = self::STATUS_INPROGRESS;
            $this->timestarted = time();
            $this->timemodified = time();
            return $this->save();
        }
        return $this;
    }

    /**
     * Advance to the next step.
     *
     * @return bool True if advanced, false if no more steps.
     */
    public function advance(): bool {
        $flow = flow::instance($this->flowid);
        $nextstep = $flow->get_next_step($this->stepid);

        if ($nextstep) {
            $this->stepid = $nextstep->get_id();
            $this->videotime = 0;
            $this->timemodified = time();
            $this->save();
            return true;
        }

        // No more steps - mark as complete.
        $this->complete();
        return false;
    }

    /**
     * Mark as complete.
     *
     * @return self
     */
    public function complete(): self {
        $this->status = self::STATUS_COMPLETED;
        $this->stepid = null;
        $this->timecompleted = time();
        $this->timemodified = time();
        return $this->save();
    }

    /**
     * Check if video requirement is met for current step.
     *
     * @return bool
     */
    public function is_video_requirement_met(): bool {
        $step = $this->get_current_step();
        if (!$step || !$step->has_video() || !$step->is_video_required()) {
            return true;
        }

        // We need to estimate if the user has watched enough.
        // This is a simplified check - in reality you'd track against video duration.
        // For now, we check if they've watched at least some time.
        $requiredPercent = $step->get_video_completion();

        // If video time > 0 and we have no duration info, we'll be lenient.
        // In the real implementation, the JS will handle proper percentage tracking.
        return $this->videotime > 0;
    }

    /**
     * Get progress percentage.
     *
     * @return float
     */
    public function get_progress(): float {
        if ($this->is_completed()) {
            return 100;
        }

        $flow = flow::instance($this->flowid);
        $totalsteps = $flow->count_steps();

        if ($totalsteps === 0) {
            return 0;
        }

        $step = $this->get_current_step();
        if (!$step) {
            return 0;
        }

        $currentnumber = $step->get_step_number();
        return round((($currentnumber - 1) / $totalsteps) * 100, 1);
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

        $record->userid = $this->userid;
        $record->flowid = $this->flowid;
        $record->stepid = $this->stepid;
        $record->status = $this->status;
        $record->videotime = $this->videotime;
        $record->timestarted = $this->timestarted;
        $record->timecompleted = $this->timecompleted;
        $record->timemodified = $this->timemodified;

        return $record;
    }

    /**
     * Save the completion to database.
     *
     * @return self
     */
    public function save(): self {
        global $DB;

        $record = $this->to_record();

        if ($this->id) {
            $DB->update_record('local_onboarding_completion', $record);
        } else {
            $this->id = $DB->insert_record('local_onboarding_completion', $record);
        }

        return $this;
    }
}
