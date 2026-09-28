# Sprinix Blogs Extension

Sprinix Blogs is a Magento 2 blog extension that allows store administrators to create and manage blog posts, categories, tags, comments, and replies directly from the Magento Admin Panel.

The extension provides configurable blog layouts, comprehensive SEO settings for the blog listing page and individual blog details pages, comments/replies management, post navigation, view counts, copy-to-clipboard functionality, and date formatting options.
## Features
-----------

### Blog Management
- Create, edit, and delete blog posts.
- Organize posts using categories.
- Add and manage tags.
- Configure blog module status.
- Show or hide the Blog link in the store menu.
- Display blog post view counts.
- Copy blog post links to the clipboard.

### Blog Layout Settings
Administrators can configure the following blog listing options:

- Show/Hide Recent Posts.
- Show/Hide Categories.
- Show/Hide Sort By.
- Show/Hide Tags.
- Show/Hide Views Count.
- Show/Hide Copy to Clipboard.

### Comments and Replies
The extension supports comments and replies on blog posts.

Available settings include:

- Enable or disable comments/replies.
- Configure the default status of new comments.
- Configure the default status of new replies.
- Set a maximum number of comments allowed from the same email address on a post.
- Configure the maximum character limit for comments and replies.

Comment/reply statuses can be configured using:

- Pending
- Approved
- Not Approved

### Previous and Next Post Navigation
Administrators can enable or disable Previous and Next Post navigation links on blog post pages.

### Blog List SEO
The blog listing page provides configurable SEO settings:

- Page Title
- Meta Title
- Meta Description
- Meta Keywords

These settings can be configured from:

`Stores > Configuration > Sprinix > Blog > Blog List SEO`

### Date Settings
Administrators can select the date format used for blog content from the available date format options.

### Individual Blog SEO

Each individual blog post provides separate SEO settings for its blog details page:

- URL Key
- Page Title
- Meta Title
- Meta Keywords
- Meta Description

## Configuration
---------------

The extension configuration is available at:

`Stores > Configuration > Sprinix > Blog`

### Blog Settings

| Setting | Description |
|--------|-------------|
| Module Enable | Enable or disable the blog module. |
| Show Blog in Menu | Show or hide the Blog link in the store menu. |

### Blog Layout Settings

| Setting | Description |
|--------|-------------|
| Show Recent Posts | Display recent blog posts. |
| Show Categories | Display blog categories. |
| Show Sort By | Display blog sorting options. |
| Show Tags | Display blog tags. |
| Show Views Count | Display blog post view counts. |
| Show Copy to Clipboard | Display the copy blog link option. |

### Comment / Reply Settings

| Setting | Description |
|--------|-------------|
| Show Comment/Reply | Enable or disable comments and replies. |
| New Reply Status | Set the default status for newly submitted replies. |
| New Comment Status | Set the default status for newly submitted comments. |
| Maximum Comments Per Email on Same Post | Limit the number of comments that can be submitted using the same email address on a post. Leave blank for unlimited comments. |
| Comment/Reply Character Limit | Set the maximum number of characters allowed in comments and replies. |

### Previous and Next Post

| Setting | Description |
|--------|-------------|
| Display Prev. & Next Post Links | Enable or disable Previous and Next Post links on blog posts. |

### Blog List SEO

| Setting | Description |
|--------|-------------|
| Page Title | Set the page title for the blog list page. |
| Meta Title | Set the meta title for the blog list page. |
| Meta Description | Set the meta description for the blog list page. |
| Meta Keywords | Set comma-separated meta keywords for the blog list page. |

### Date Settings

| Setting | Description |
|--------|-------------|
| Choose Date Format | Select the date format displayed for blog content. |

## Compatibility
---------------

The extension is compatible with the following Magento 2 versions:

- Magento 2.4.3
- Magento 2.4.4
- Magento 2.4.5
- Magento 2.4.6
- Magento 2.4.7
- Magento 2.4.8
- Magento 2.4.9

## Installation Instructions
----------------------------

### Copy and Paste

1. Download the `Blogs.zip` file.

2. Extract the `Blogs.zip` file into:

```text
app/code/Sprinix/Blogs
