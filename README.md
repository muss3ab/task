# Laravel Authentication and Blog API
A RESTful API built with Laravel that provides authentication, blog post management, and tag management functionality. The system includes phone verification, soft deletes, scheduled tasks, and statistical tracking.

## Features

### Authentication System
- Phone number-based authentication using Laravel Sanctum
- 6-digit verification code system
- Secure token-based API access
- Only verified accounts can access the system

### Tags Management
- CRUD operations for tags
- Unique tag names
- Protected routes for authenticated users

### Posts Management
- Create, read, update, and soft delete operations
- Image upload functionality
- Post pinning capability
- Tag association (many-to-many relationship)
- Restore soft-deleted posts
- View user's deleted posts
- Automatic removal of 30+ days deleted posts

### Additional Features
- Random user data fetching (every 6 hours)
- Statistical endpoints with caching
- Proper data validation
- Authorization policies

## Requirements

- PHP 8.2+
- Laravel 11.x
- SQLite/MySQL
- Composer

## Installation

1. Clone the repository
```bash
git clone https://github.com/muss3ab/task.git
cd task
```

2. Install dependencies
```bash
composer install
```

3. Create and configure environment file
```bash
cp .env.example .env
```

4. For SQLite database, create database file
```bash
touch database/database.sqlite
```

5. Update .env file with database configuration
```
DB_CONNECTION=sqlite
```

6. Generate application key ifn
```bash
php artisan key:generate
```

7. Run migrations
```bash
php artisan migrate
```

8. Create symbolic link for storage
```bash
php artisan storage:link
```
9. run app 
```bash
php artisan serve
```
10. run schedule 
```bash 
php artisan schedule:work
php artisan queue:work
```

## API Endpoints

### Authentication
header api
Authorization: Bearer 2|KxEiB78wxK81Q8ZCTURAfFHQfYJHGHMsTmpEiiYF853f0867

```
POST /api/register
{
    "name": "string",
    "phone": "string",
    "password": "string"
}

POST /api/login
{
    "phone": "string",
    "password": "string"
}

POST /api/verify
{
    "phone": "string",
    "verification_code": "string"
}
```

### Tags

All routes require authentication token.

```
GET /api/tags
POST /api/tags
{
    "name": "string"
}
PUT /api/tags/{id}
{
    "name": "string"
}
DELETE /api/tags/{id}
```

### Posts

All routes require authentication token.

```
GET /api/posts
POST /api/posts
{
    "title": "string",
    "body": "string",
    "cover_image": "file",
    "pinned": boolean,
    "tags": [1, 2, ...]
}
GET /api/posts/{id}
PUT /api/posts/{id}
{
    "title": "string",
    "body": "string",
    "cover_image": "file" (optional),
    "pinned": boolean,
    "tags": [1, 2, ...]
}
DELETE /api/posts/{id}
GET /api/posts/deleted/list
POST /api/posts/{id}/restore
```

### Statistics

```
GET /api/stats
Returns:
{
    "total_users": integer,
    "total_posts": integer,
    "users_without_posts": integer
}
```

## Validation Rules

### User Registration
- Name: Required, string, max 255 characters
- Phone: Required, string, unique
- Password: Required, string, min 8 characters

### Posts
- Title: Required, string, max 255 characters
- Body: Required, string
- Cover Image: Required when creating, optional when updating, must be image
- Pinned: Required, boolean
- Tags: Required, array of existing tag IDs

### Tags
- Name: Required, string, unique, max 255 characters

## Scheduled Tasks

The application includes two scheduled tasks:

1. Post Cleanup (Daily)
   - Permanently removes posts that have been soft-deleted for more than 30 days

2. Random User Fetch (Every 6 hours)
   - Fetches random user data from https://randomuser.me/api/
   - Logs the results

To run the scheduler:
```bash
php artisan schedule:work
php artisan queue:work
```

## Cache

Statistics are cached and automatically updated when:
- New users are registered
- Posts are created or deleted

## File Storage

Post cover images are stored in the public disk under the 'covers' directory.

## Security

- All API routes (except registration and login) are protected by Sanctum authentication
- Users can only access their own posts
- Phone verification is required for login
- Proper validation and sanitization of all inputs

## Error Handling

The API returns appropriate HTTP status codes:
- 200: Successful operation
- 201: Resource created
- 400: Bad request
- 401: Unauthorized
- 403: Forbidden
- 404: Resource not found
- 422: Validation error
- 500: Server error

## Contributing

[Add your contribution guidelines here]

## License

[Add your license information here]


I've created a comprehensive README.md file that covers:
1. All features and functionality
2. Installation instructions
3. API endpoint documentation
4. Validation rules
5. Scheduled tasks
6. Caching mechanism
7. Security features
8. Error handling

You can customize it further by:
1. Adding specific contribution guidelines
2. Including license information
3. Adding more detailed setup instructions
4. Including troubleshooting guides
5. Adding example API responses
6. Including development environment setup instructions

Would you like me to add any of these sections or modify the existing content?