# Blog Module

A Laravel module for managing blog posts with repository pattern architecture.

## Structure

```
Blog/
├── app/
│   ├── Contracts/
│   │   └── PostRepositoryInterface.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── BlogController.php
│   │   └── Requests/
│   │       ├── StorePostRequest.php
│   │       └── UpdatePostRequest.php
│   ├── Models/
│   │   └── Post.php
│   ├── Providers/
│   │   └── BlogServiceProvider.php
│   ├── Repositories/
│   │   └── PostRepository.php
│   └── Services/
│       └── PostService.php
├── config/
├── database/
│   └── migrations/
├── resources/
├── routes/
└── tests/
```

## Installation

1. Clone this repository into `Modules/Blog` directory
2. Register the module in `config/modules.php`
3. Run migrations: `php artisan migrate`

## Architecture

This module follows the **Repository Pattern**:

- **Controller** → Handles HTTP requests
- **Service** → Contains business logic
- **Repository** → Data access layer
- **Model** → Eloquent ORM

## API

### PostService Methods

- `getAllPosts()` - Get all posts
- `getPaginatedPosts($perPage)` - Get paginated posts
- `getPostById($id)` - Get post by ID
- `getPostBySlug($slug)` - Get post by slug
- `createPost($data)` - Create a new post
- `updatePost($id, $data)` - Update a post
- `deletePost($id)` - Delete a post
- `getPublishedPosts($perPage)` - Get published posts
- `getDraftPosts($perPage)` - Get draft posts
