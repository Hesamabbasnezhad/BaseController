# BaseController

A base controller for Laravel designed to simplify and standardize response handling in your API.  
It wraps your logic in a clean `handleRequest()` method that automatically returns consistent success or error responses using a `ResponseBuilder` service.

---

## 📦 Features

- Handles success, created, unauthorized, and error responses
- Centralized try/catch structure
- Supports flexible callback logic
- Reduces repetition in API controllers

---

## 📁 Requirements

You must have a `ResponseBuilder` class/service that handles response formatting.  
Example methods inside `ResponseBuilder` might include:

- `success($data, $message = null)`
- `created($data, $message = null)`
- `unAuthorized($data, $message = null)`
- `error($exception)`

---

## 🛠️ Usage

### Extend BaseController in your own controller

```php
use App\Services\BaseController;

class UserController extends BaseController
{
    public function getProfile()
    {
        return $this->handleRequest(function () {
            $user = auth()->user();

            return [
                'type' => 'success',
                'data' => $user,
                'message' => 'User profile retrieved successfully'
            ];
        });
    }
}
