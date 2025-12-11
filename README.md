# Contact Form Pro

## Overview

Contact Form Pro is a lightweight WordPress plugin that provides a shortcode-based contact form system using Custom Post Types (CPT). All submissions are stored as CPT posts and can be viewed, searched, filtered, and exported inside the WordPress admin panel.

## Features

- Create forms using the cfp_form custom post type
- Insert a form anywhere using a shortcode
- Store submissions as cfp_submission posts
- Validate and sanitize form input
- Nonce security for safe form submission
- Admin submissions page with:
  - Search
  - Filter by Form ID
  - Pagination
  - CSV export
- Separate frontend and admin CSS

## Shortcode

### Usage

```
[cfp_form id="FORM_POST_ID"]
```

### Example

```
[cfp_form id="123"]
```

## Submission Data

Each submission is stored as a `cfp_submission` post with the following meta values:

| Meta Key    |     Description      |
| ----------- | :------------------: |
| cfp_name    |    Sender's name     |
| cfp_email   |    Sender's email    |
| cfp_subject |     Subject line     |
| cfp_message |     Message body     |
| cfp_form_id | Related form post ID |

## Folder Structure

```
contact-form-pro/
│
├── contact-form-pro.php
│
├── assets/
│   └── css/
│       ├── admin.css
│       └── frontend.css
│
└── includes/
    ├── cpt-form.php
    ├── cpt-submission.php
    ├── shortcode-form.php
    ├── handle-submission.php
    ├── admin-menu.php
    └── admin-submissions-page.php
```

## Installation

1. Upload the plugin folder to:

```
/wp-content/plugins/contact-form-pro/
```

2. Activate the plugin through the WordPress admin panel
3. Create a form under:

```
Contact Forms → Add New
```

4. Copy the generated shortcode
5. Paste it into any post or page

## Admin Submissions Page

The Submissions page includes:

- Entry listing
- Search box
- Filter by Form ID
- Pagination
- CSV export button

The CSV file contains:
| Column | Description |
| ------------- |:-------------:|
| ID | Submission ID |
| Form ID | Related form |
| Name | Sender name |
| Email | Sender email |
| Subject | Subject |
| Message | Message |
| Date | Submission date |

## Blocks of Code

### Display a form:

```
[cfp_form id="123"]
```

### Hook when submission is saved:

```
do_action('cfp_submission_saved', $submission_id, $data);
```

## Security

- Nonce verification
- Input sanitization
- Email validation
- Safe redirects
- Submissions are not publicly accessible

## Inline Code

The plugin uses `wp_nonce_field()` and `wp_verify_nonce()` to ensure secure submissions.
