/**
 * Onboarding UI controller
 *
 * @module     local_onboarding/onboarding
 * @copyright  2025 BixAgency.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core/ajax'], function(Ajax) {
    'use strict';

    /** @type {Object} Configuration */
    var config = {
        flowId: null,
        stepId: null,
        hasVideo: false,
        videoRequired: false,
        videoCompletion: 80,
        returnUrl: ''
    };

    /** @type {number} Video progress percentage */
    var videoProgress = 0;

    /** @type {boolean} Whether video requirement is met */
    var videoRequirementMet = false;

    /** @type {HTMLElement} Next button element */
    var nextButton = null;

    /** @type {HTMLElement} Video progress bar */
    var progressBar = null;

    /** @type {HTMLElement} Video notice element */
    var videoNotice = null;

    /** @type {Object} YouTube player instance */
    var ytPlayer = null;

    /** @type {number} Progress polling interval */
    var progressPollInterval = null;

    /**
     * Initialize the onboarding experience.
     *
     * @param {Object} options Configuration options
     */
    var init = function(options) {
        config = Object.assign({}, config, options);

        nextButton = document.getElementById('onboarding-next');
        progressBar = document.getElementById('video-progress');
        videoNotice = document.getElementById('video-notice');

        if (config.hasVideo && config.videoRequired) {
            setupVideoTracking();
        } else {
            videoRequirementMet = true;
            updateNextButton();
        }

        setupKeyboardNav();
        setupThemeToggle();
    };

    /**
     * Setup theme toggle functionality.
     */
    var setupThemeToggle = function() {
        var container = document.getElementById('onboarding-container');
        var toggleBtn = document.getElementById('theme-toggle');

        if (!container || !toggleBtn) {
            return;
        }

        // Check saved preference or system preference
        var savedTheme = localStorage.getItem('onboarding-theme');
        if (savedTheme) {
            applyTheme(savedTheme, container);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            applyTheme('dark', container);
        }

        // Toggle button click handler
        toggleBtn.addEventListener('click', function() {
            var isDark = container.classList.contains('onboarding-dark');
            var newTheme = isDark ? 'light' : 'dark';
            applyTheme(newTheme, container);
            localStorage.setItem('onboarding-theme', newTheme);
        });

        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('onboarding-theme')) {
                    applyTheme(e.matches ? 'dark' : 'light', container);
                }
            });
        }
    };

    /**
     * Apply theme to container.
     *
     * @param {string} theme 'light' or 'dark'
     * @param {HTMLElement} container The onboarding container
     */
    var applyTheme = function(theme, container) {
        container.classList.remove('onboarding-light', 'onboarding-dark');
        container.classList.add('onboarding-' + theme);
    };

    /**
     * Setup video tracking for YouTube/Vimeo.
     */
    var setupVideoTracking = function() {
        var iframe = document.querySelector('#onboarding-video-' + config.stepId);

        if (!iframe) {
            videoRequirementMet = true;
            updateNextButton();
            return;
        }

        var src = iframe.src || '';

        if (src.indexOf('youtube.com') !== -1 || src.indexOf('youtu.be') !== -1) {
            setupYouTubeTracking(iframe);
        } else if (src.indexOf('vimeo.com') !== -1) {
            setupVimeoTracking(iframe);
        } else {
            // Unknown video source - be lenient
            videoRequirementMet = true;
            updateNextButton();
        }
    };

    /**
     * Setup YouTube video tracking using the IFrame Player API.
     *
     * @param {HTMLIFrameElement} iframe The video iframe
     */
    var setupYouTubeTracking = function(iframe) {
        if (window.YT && window.YT.Player) {
            initYouTubePlayer(iframe);
        } else {
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            window.onboardingIframe = iframe;

            window.onYouTubeIframeAPIReady = function() {
                initYouTubePlayer(window.onboardingIframe);
            };
        }
    };

    /**
     * Initialize YouTube player on existing iframe.
     *
     * @param {HTMLIFrameElement} iframe The video iframe
     */
    var initYouTubePlayer = function(iframe) {
        try {
            ytPlayer = new window.YT.Player(iframe, {
                events: {
                    'onReady': onYouTubePlayerReady,
                    'onStateChange': onYouTubePlayerStateChange,
                    'onError': function() {
                        // On error, be lenient and enable button
                        videoRequirementMet = true;
                        updateNextButton();
                    }
                }
            });
        } catch (e) {
            videoRequirementMet = true;
            updateNextButton();
        }
    };

    /**
     * YouTube player ready handler.
     */
    var onYouTubePlayerReady = function() {
        startProgressPolling();
    };

    /**
     * YouTube player state change handler.
     *
     * @param {Object} event The state change event
     */
    var onYouTubePlayerStateChange = function(event) {
        // State 1 = playing
        if (event.data === 1) {
            startProgressPolling();
        }
    };

    /**
     * Start polling for video progress.
     */
    var startProgressPolling = function() {
        if (progressPollInterval) {
            return;
        }

        progressPollInterval = setInterval(function() {
            if (!ytPlayer) {
                return;
            }

            try {
                var currentTime = ytPlayer.getCurrentTime ? ytPlayer.getCurrentTime() : 0;
                var duration = ytPlayer.getDuration ? ytPlayer.getDuration() : 0;

                if (duration > 0) {
                    var percent = (currentTime / duration) * 100;
                    updateVideoProgress(percent);
                }
            } catch (e) {
                // Player not ready yet, ignore
            }

            if (videoRequirementMet) {
                clearInterval(progressPollInterval);
                progressPollInterval = null;
            }
        }, 1000);
    };

    /**
     * Setup Vimeo video tracking.
     *
     * @param {HTMLIFrameElement} iframe The video iframe
     */
    var setupVimeoTracking = function(iframe) {
        var tag = document.createElement('script');
        tag.src = 'https://player.vimeo.com/api/player.js';
        tag.onload = function() {
            if (window.Vimeo && window.Vimeo.Player) {
                var player = new window.Vimeo.Player(iframe);

                player.on('timeupdate', function(data) {
                    var percent = data.percent * 100;
                    updateVideoProgress(percent);
                });
            } else {
                videoRequirementMet = true;
                updateNextButton();
            }
        };
        tag.onerror = function() {
            videoRequirementMet = true;
            updateNextButton();
        };
        document.head.appendChild(tag);
    };

    /**
     * Update video progress.
     *
     * @param {number} percent Percentage watched (0-100)
     */
    var updateVideoProgress = function(percent) {
        videoProgress = Math.max(videoProgress, percent);

        if (progressBar) {
            var displayPercent = Math.min(videoProgress, 100);
            progressBar.style.width = displayPercent + '%';
            progressBar.setAttribute('aria-valuenow', Math.round(displayPercent));
        }

        if (videoProgress >= config.videoCompletion && !videoRequirementMet) {
            videoRequirementMet = true;
            updateNextButton();

            if (videoNotice) {
                videoNotice.classList.add('completed');
                var noticeText = videoNotice.querySelector('.notice-text');
                if (noticeText) {
                    noticeText.innerHTML = '<span class="notice-icon">✓</span> Great job! You can now continue to the next step.';
                }
            }

            saveVideoProgress();
        }
    };

    /**
     * Update next button state.
     */
    var updateNextButton = function() {
        if (nextButton) {
            nextButton.disabled = !videoRequirementMet;

            if (videoRequirementMet) {
                nextButton.classList.add('btn-ready');
            }
        }
    };

    /**
     * Save video progress to server.
     */
    var saveVideoProgress = function() {
        Ajax.call([{
            methodname: 'local_onboarding_update_video_time',
            args: {
                flowid: config.flowId,
                stepid: config.stepId,
                seconds: Math.floor(videoProgress)
            }
        }])[0].catch(function() {
            // Silently fail - don't interrupt user experience
        });
    };

    /**
     * Setup keyboard navigation.
     */
    var setupKeyboardNav = function() {
        document.addEventListener('keydown', function(event) {
            if ((event.key === 'Enter' || event.key === ' ') && document.activeElement === nextButton) {
                if (!nextButton.disabled) {
                    nextButton.click();
                }
                event.preventDefault();
            }
        });
    };

    return {
        init: init
    };
});
