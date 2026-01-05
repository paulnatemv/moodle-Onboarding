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
 * Step entity class.
 *
 * @package    local_onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class step {

    /** Step type constants. */
    const TYPE_CONTENT = 'content';
    const TYPE_VIDEO = 'video';
    const TYPE_IMAGE = 'image';
    const TYPE_MIXED = 'mixed';

    /** @var int|null The step ID. */
    protected $id;

    /** @var int The flow ID. */
    protected $flowid;

    /** @var string The step title. */
    protected $title;

    /** @var string The step content. */
    protected $content;

    /** @var int Content format. */
    protected $contentformat;

    /** @var string Step type. */
    protected $steptype;

    /** @var string|null Video URL. */
    protected $videourl;

    /** @var bool Is video required. */
    protected $videorequired;

    /** @var int Video completion percentage. */
    protected $videocompletion;

    /** @var string|null Image URL. */
    protected $imageurl;

    /** @var string|null CTA button text. */
    protected $ctabutton;

    /** @var string|null CTA URL. */
    protected $ctaurl;

    /** @var bool CTA opens in new tab. */
    protected $ctanewtab;

    /** @var int Sort order. */
    protected $sortorder;

    /** @var int Time created. */
    protected $timecreated;

    /** @var int Time modified. */
    protected $timemodified;

    /**
     * Create a step instance from an ID.
     *
     * @param int $id The step ID.
     * @return self
     * @throws \dml_exception If step not found.
     */
    public static function instance(int $id): self {
        global $DB;

        $record = $DB->get_record('local_onboarding_steps', ['id' => $id], '*', MUST_EXIST);
        return self::from_record($record);
    }

    /**
     * Create a step instance from a database record.
     *
     * @param \stdClass $record The database record.
     * @return self
     */
    public static function from_record(\stdClass $record): self {
        $step = new self();
        $step->id = (int) $record->id;
        $step->flowid = (int) $record->flowid;
        $step->title = $record->title;
        $step->content = $record->content ?? '';
        $step->contentformat = (int) ($record->contentformat ?? FORMAT_HTML);
        $step->steptype = $record->steptype ?? self::TYPE_CONTENT;
        $step->videourl = $record->videourl ?? null;
        $step->videorequired = (bool) ($record->videorequired ?? false);
        $step->videocompletion = (int) ($record->videocompletion ?? 80);
        $step->imageurl = $record->imageurl ?? null;
        $step->ctabutton = $record->ctabutton ?? null;
        $step->ctaurl = $record->ctaurl ?? null;
        $step->ctanewtab = (bool) ($record->ctanewtab ?? false);
        $step->sortorder = (int) $record->sortorder;
        $step->timecreated = (int) $record->timecreated;
        $step->timemodified = (int) $record->timemodified;

        return $step;
    }

    /**
     * Get all steps for a flow.
     *
     * @param int $flowid The flow ID.
     * @return array Array of step objects.
     */
    public static function get_for_flow(int $flowid): array {
        global $DB;

        $records = $DB->get_records('local_onboarding_steps', ['flowid' => $flowid], 'sortorder ASC');

        $steps = [];
        foreach ($records as $record) {
            $steps[] = self::from_record($record);
        }

        return $steps;
    }

    /**
     * Create a new step.
     *
     * @param int $flowid The flow ID.
     * @return self
     */
    public static function create(int $flowid): self {
        $step = new self();
        $step->flowid = $flowid;
        $step->steptype = self::TYPE_CONTENT;
        $step->contentformat = FORMAT_HTML;
        $step->videorequired = false;
        $step->videocompletion = 80;
        $step->ctanewtab = false;
        $step->sortorder = self::get_next_sortorder($flowid);

        return $step;
    }

    /**
     * Get the next sortorder for a flow.
     *
     * @param int $flowid The flow ID.
     * @return int
     */
    private static function get_next_sortorder(int $flowid): int {
        global $DB;

        $maxsortorder = $DB->get_field('local_onboarding_steps', 'MAX(sortorder)', ['flowid' => $flowid]);
        return ($maxsortorder ?? -1) + 1;
    }

    /**
     * Get the step ID.
     *
     * @return int|null
     */
    public function get_id(): ?int {
        return $this->id;
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
     * Get the step title.
     *
     * @return string
     */
    public function get_title(): string {
        return $this->title ?? '';
    }

    /**
     * Set the step title.
     *
     * @param string $title
     * @return self
     */
    public function set_title(string $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the step content.
     *
     * @return string
     */
    public function get_content(): string {
        return $this->content ?? '';
    }

    /**
     * Set the step content.
     *
     * @param string $content
     * @param int $format
     * @return self
     */
    public function set_content(string $content, int $format = FORMAT_HTML): self {
        $this->content = $content;
        $this->contentformat = $format;
        return $this;
    }

    /**
     * Get formatted content.
     *
     * @return string
     */
    public function get_formatted_content(): string {
        return format_text($this->content, $this->contentformat);
    }

    /**
     * Get the step type.
     *
     * @return string
     */
    public function get_steptype(): string {
        return $this->steptype;
    }

    /**
     * Set the step type.
     *
     * @param string $type
     * @return self
     */
    public function set_steptype(string $type): self {
        $this->steptype = $type;
        return $this;
    }

    /**
     * Check if step has video.
     *
     * @return bool
     */
    public function has_video(): bool {
        return !empty($this->videourl) &&
               in_array($this->steptype, [self::TYPE_VIDEO, self::TYPE_MIXED]);
    }

    /**
     * Get video URL.
     *
     * @return string|null
     */
    public function get_videourl(): ?string {
        return $this->videourl;
    }

    /**
     * Set video URL.
     *
     * @param string|null $url
     * @return self
     */
    public function set_videourl(?string $url): self {
        $this->videourl = $url;
        return $this;
    }

    /**
     * Get embed URL for video.
     *
     * @return string|null
     */
    public function get_video_embed_url(): ?string {
        if (empty($this->videourl)) {
            return null;
        }

        $url = $this->videourl;

        // YouTube.
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            global $CFG;
            $origin = rtrim($CFG->wwwroot, '/');
            return 'https://www.youtube.com/embed/' . $matches[1] . '?enablejsapi=1&rel=0&origin=' . urlencode($origin);
        }

        // Vimeo.
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1] . '?api=1';
        }

        // Assume it's already an embed URL or direct video URL.
        return $url;
    }

    /**
     * Check if video is required.
     *
     * @return bool
     */
    public function is_video_required(): bool {
        return $this->videorequired;
    }

    /**
     * Set video required.
     *
     * @param bool $required
     * @return self
     */
    public function set_video_required(bool $required): self {
        $this->videorequired = $required;
        return $this;
    }

    /**
     * Get video completion percentage.
     *
     * @return int
     */
    public function get_video_completion(): int {
        return $this->videocompletion;
    }

    /**
     * Set video completion percentage.
     *
     * @param int $percent
     * @return self
     */
    public function set_video_completion(int $percent): self {
        $this->videocompletion = min(100, max(0, $percent));
        return $this;
    }

    /**
     * Check if step has image.
     *
     * @return bool
     */
    public function has_image(): bool {
        return !empty($this->imageurl);
    }

    /**
     * Get image URL.
     *
     * @return string|null
     */
    public function get_imageurl(): ?string {
        return $this->imageurl;
    }

    /**
     * Set image URL.
     *
     * @param string|null $url
     * @return self
     */
    public function set_imageurl(?string $url): self {
        $this->imageurl = $url;
        return $this;
    }

    /**
     * Check if step has CTA.
     *
     * @return bool
     */
    public function has_cta(): bool {
        return !empty($this->ctabutton) && !empty($this->ctaurl);
    }

    /**
     * Get CTA button text.
     *
     * @return string|null
     */
    public function get_ctabutton(): ?string {
        return $this->ctabutton;
    }

    /**
     * Set CTA button text.
     *
     * @param string|null $text
     * @return self
     */
    public function set_ctabutton(?string $text): self {
        $this->ctabutton = $text;
        return $this;
    }

    /**
     * Get CTA URL.
     *
     * @return string|null
     */
    public function get_ctaurl(): ?string {
        return $this->ctaurl;
    }

    /**
     * Set CTA URL.
     *
     * @param string|null $url
     * @return self
     */
    public function set_ctaurl(?string $url): self {
        $this->ctaurl = $url;
        return $this;
    }

    /**
     * Check if CTA opens in new tab.
     *
     * @return bool
     */
    public function cta_opens_new_tab(): bool {
        return $this->ctanewtab;
    }

    /**
     * Set CTA new tab.
     *
     * @param bool $newtab
     * @return self
     */
    public function set_cta_newtab(bool $newtab): self {
        $this->ctanewtab = $newtab;
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
     * Get step number (1-based).
     *
     * @return int
     */
    public function get_step_number(): int {
        $steps = self::get_for_flow($this->flowid);
        $number = 1;

        foreach ($steps as $step) {
            if ($step->get_id() === $this->id) {
                return $number;
            }
            $number++;
        }

        return $number;
    }

    /**
     * Check if this is the first step.
     *
     * @return bool
     */
    public function is_first_step(): bool {
        $steps = self::get_for_flow($this->flowid);
        $first = reset($steps);
        return $first && $first->get_id() === $this->id;
    }

    /**
     * Check if this is the last step.
     *
     * @return bool
     */
    public function is_last_step(): bool {
        $steps = self::get_for_flow($this->flowid);
        $last = end($steps);
        return $last && $last->get_id() === $this->id;
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

        $record->flowid = $this->flowid;
        $record->title = $this->title;
        $record->content = $this->content ?? '';
        $record->contentformat = $this->contentformat ?? FORMAT_HTML;
        $record->steptype = $this->steptype;
        $record->videourl = $this->videourl;
        $record->videorequired = $this->videorequired ? 1 : 0;
        $record->videocompletion = $this->videocompletion;
        $record->imageurl = $this->imageurl;
        $record->ctabutton = $this->ctabutton;
        $record->ctaurl = $this->ctaurl;
        $record->ctanewtab = $this->ctanewtab ? 1 : 0;
        $record->sortorder = $this->sortorder;
        $record->timemodified = time();

        if (!$this->id) {
            $record->timecreated = time();
        }

        return $record;
    }

    /**
     * Save the step to database.
     *
     * @return self
     */
    public function save(): self {
        global $DB;

        $record = $this->to_record();

        if ($this->id) {
            $DB->update_record('local_onboarding_steps', $record);
        } else {
            $this->id = $DB->insert_record('local_onboarding_steps', $record);
        }

        return $this;
    }

    /**
     * Delete the step.
     *
     * @return void
     */
    public function delete(): void {
        global $DB;

        if (!$this->id) {
            return;
        }

        $DB->delete_records('local_onboarding_steps', ['id' => $this->id]);
    }

    /**
     * Export for template.
     *
     * @return array
     */
    public function export_for_template(): array {
        $context = \context_system::instance();

        return [
            'id' => $this->id,
            'flowid' => $this->flowid,
            'title' => format_string($this->title, true, ['context' => $context]),
            'content' => $this->get_formatted_content(),
            'steptype' => $this->steptype,
            'hasvideo' => $this->has_video(),
            'videourl' => $this->videourl,
            'videoembedurl' => $this->get_video_embed_url(),
            'videorequired' => $this->videorequired,
            'videocompletion' => $this->videocompletion,
            'hasimage' => $this->has_image(),
            'imageurl' => $this->imageurl,
            'hascta' => $this->has_cta(),
            'ctabutton' => format_string($this->ctabutton ?? '', true, ['context' => $context]),
            'ctaurl' => $this->ctaurl,
            'ctanewtab' => $this->ctanewtab,
            'sortorder' => $this->sortorder,
            'stepnumber' => $this->get_step_number(),
            'isfirst' => $this->is_first_step(),
            'islast' => $this->is_last_step(),
        ];
    }
}
