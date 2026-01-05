# Moodle Onboarding Plugin

A mobile-first onboarding plugin for Moodle that guides new users through welcome content before they can access the platform.

![Moodle](https://img.shields.io/badge/Moodle-4.5%2B-orange)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![License](https://img.shields.io/badge/License-GPL%20v3-green)

## The Problem

When new users sign up for your Moodle site, they often:
- Don't understand how to navigate the platform
- Miss important information about your courses or services
- Skip directly to content without understanding the value proposition
- Have lower engagement and higher drop-off rates

## The Solution

This plugin lets administrators create **mandatory onboarding flows** that new users must complete before accessing Moodle. Think of it as a welcome wizard that ensures every user gets properly introduced to your platform.

### Key Features

- **Multi-step onboarding flows** - Create sequences of welcome screens
- **Video support** - Embed YouTube or Vimeo videos with progress tracking
- **Mandatory video watching** - Require users to watch X% of a video before proceeding
- **Mobile-first design** - Works beautifully on phones, tablets, and desktops
- **Role targeting** - Show different onboarding flows to different user types
- **CTA buttons** - Add call-to-action buttons linking to any URL
- **Completion tracking** - See who completed onboarding and when
- **Admin dashboard** - Easy drag-and-drop step management
- **API for integrations** - Works with n8n, Zapier, and other automation tools

## Screenshots

### User Onboarding Experience
The clean, distraction-free interface guides users through each step:

```
┌─────────────────────────────────────────┐
│  ① Welcome  ② How It Works  ③ Start    │
├─────────────────────────────────────────┤
│                                         │
│        Welcome to Our Platform!         │
│                                         │
│    ┌─────────────────────────────┐      │
│    │                             │      │
│    │      [Video Player]        │      │
│    │                             │      │
│    └─────────────────────────────┘      │
│                                         │
│    Watch this short video to continue   │
│    ████████████░░░░ 75%                 │
│                                         │
│           [ Continue → ]                │
│                                         │
└─────────────────────────────────────────┘
```

## Installation

### Method 1: Git Clone
```bash
cd /path/to/moodle/local
git clone https://github.com/paulnatemv/moodle-Onboarding.git onboarding
```

### Method 2: Download ZIP
1. Download the latest release
2. Extract to `/path/to/moodle/local/onboarding`

### Complete Installation
1. Log in as admin
2. Go to **Site Administration → Notifications**
3. Follow the upgrade prompts
4. Go to **Site Administration → Plugins → Local plugins → Onboarding Settings**
5. Enable the plugin

## Quick Start Guide

### 1. Enable the Plugin
Go to **Site Administration → Plugins → Local plugins → Onboarding Settings**
- Check "Enable onboarding"
- Set default video completion percentage (e.g., 80%)

### 2. Create Your First Flow
Go to **Site Administration → Plugins → Local plugins → Manage Onboarding Flows**
1. Click **"Add new flow"**
2. Enter a name (e.g., "New Student Welcome")
3. Leave "Target roles" empty to show to ALL users
4. Save

### 3. Add Steps to Your Flow
1. Click **"Manage Steps"** on your flow
2. Click **"Add new step"**
3. Configure your step:
   - **Title**: "Welcome!"
   - **Step type**: Video
   - **Video URL**: Paste a YouTube or Vimeo link
   - **Video must be watched**: Yes
   - **Required watch percentage**: 80%
4. Add more steps as needed

### 4. Test It
1. Log out
2. Log in as a non-admin user
3. You'll be redirected to the onboarding flow!

## Configuration Options

### Global Settings
| Setting | Description |
|---------|-------------|
| Enable onboarding | Turn the plugin on/off globally |
| Default video completion | Default % users must watch (0-100) |
| Show to administrators | Whether admins see onboarding too |

### Per-Flow Settings
| Setting | Description |
|---------|-------------|
| Flow name | Display name for the flow |
| Enabled | Whether this flow is active |
| Mandatory | Users must complete before accessing site |
| Target roles | Which roles see this flow (empty = everyone) |
| Redirect URL | Where to send users after completion |

### Per-Step Settings
| Setting | Description |
|---------|-------------|
| Title | Step heading |
| Content | Rich text content (HTML supported) |
| Step type | Content only, Video, Image, or Mixed |
| Video URL | YouTube or Vimeo URL |
| Video required | Must user watch to proceed? |
| Watch percentage | How much must be watched (0-100%) |
| CTA button | Optional call-to-action button |
| CTA URL | Where the button links to |

## Step Types

### Content Only
Plain text/HTML content for announcements, instructions, or information.

### Video
Embed YouTube or Vimeo videos with optional watch tracking.

**Supported formats:**
- `https://www.youtube.com/watch?v=VIDEO_ID`
- `https://youtu.be/VIDEO_ID`
- `https://vimeo.com/VIDEO_ID`

### Image
Display an image with optional accompanying text.

### Mixed
Combine video with additional content below it.

## API Integration (n8n, Zapier, etc.)

The plugin provides REST API endpoints for automation workflows.

### Setup
1. Go to **Site Administration → Server → Web services → External services**
2. Find "Onboarding External API"
3. Create a token for an admin user

### Available Endpoints

#### Get User Status
Check if a user completed onboarding:
```
GET /webservice/rest/server.php
?wstoken=YOUR_TOKEN
&wsfunction=local_onboarding_get_user_status
&moodlewsrestformat=json
&email=user@example.com
```

#### Reset User Completion
Force a user to re-do onboarding:
```
POST /webservice/rest/server.php
?wstoken=YOUR_TOKEN
&wsfunction=local_onboarding_reset_user_completion
&moodlewsrestformat=json
&email=user@example.com
```

#### Get Completion Report
Get all completion data:
```
GET /webservice/rest/server.php
?wstoken=YOUR_TOKEN
&wsfunction=local_onboarding_get_completion_report
&moodlewsrestformat=json
&status=completed
&limit=100
```

### n8n Example Workflow
1. **Trigger**: New user signup (webhook)
2. **HTTP Request**: Check onboarding status
3. **IF**: Not completed after 7 days
4. **Send Email**: Reminder to complete onboarding

## Reports

### Completion Report
**Site Administration → Plugins → Local plugins → Completion Report**

View:
- Total users who started onboarding
- Completion rates
- In-progress users
- Individual user completion details

### Reset Completions
**Site Administration → Plugins → Local plugins → Reset User Completions**

Options:
- Reset ALL users (everyone sees onboarding again)
- Reset specific flow completions

## How It Works

```
User Logs In
     ↓
Plugin checks: Enabled? User not admin? Active flow exists?
     ↓
Check: Has user completed this flow?
     ↓
NO → Redirect to onboarding
     ↓
User progresses through steps
     ↓
Video steps: Track watch progress
     ↓
All steps complete → Mark as "completed"
     ↓
Redirect to dashboard (or custom URL)
     ↓
NEXT LOGIN: User goes directly to dashboard
```

## Capabilities

| Capability | Description |
|------------|-------------|
| `local/onboarding:manageflows` | Create, edit, delete flows and steps |
| `local/onboarding:viewreports` | View completion reports |
| `local/onboarding:bypass` | Skip onboarding entirely |

## Requirements

- Moodle 4.5 or higher (compatible with Moodle 5)
- PHP 8.1 or higher

## Troubleshooting

### Users not seeing onboarding
1. Check plugin is enabled in settings
2. Verify an enabled flow exists
3. Check target roles (empty = all users)
4. Ensure user doesn't have bypass capability

### Video progress not tracking
1. Ensure video URL is from YouTube or Vimeo
2. Check browser console for JavaScript errors
3. Verify the video allows embedding

### Admin can't test onboarding
Enable "Show to administrators" in plugin settings.

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Submit a pull request

## License

This plugin is licensed under the [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html).

## Credits

Developed by [BixAgency.com](https://bixagency.com)

## Support

- **Issues**: [GitHub Issues](https://github.com/paulnatemv/moodle-Onboarding/issues)
- **Documentation**: This README
- **Moodle Plugins Directory**: Coming soon

---

**Made with love for the Moodle community**
