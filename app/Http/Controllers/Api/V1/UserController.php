<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Mail\ConfirmPasswordReset;
use App\Mail\PasswordResetNotification;
use App\Models\Api\V1\PasswordResetToken;
use App\Models\Api\V1\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService)
    {
        $this->authorizeResource(User::class, 'user');

    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        /*$users = UserResource::collection(User::all());
        return $this->sendResponse($users);*/
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        // Create a new user using the UserService.
        $user = $this->userService->create($request->validated());
        // Return the response with the created user resource.
        return $this->sendResponse(new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        // Return the response with the specified user resource.
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        // Update the user using the UserService.
        $this->userService->update($request->validated(), $user);
        // Return the response with the updated user.
        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Delete the user from the database.
        $user->delete();
        // Return the response indicating that the user has been deleted.
        return $this->sendResponse('user deleted successfully');
    }


    /**
     * Method to handle the forgot password request.
     *
     * @throws \Exception
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Retrieve the user associated with the specified email address.
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        // Check if the user's email is null and return an error if it is.
        if (is_null($user->email)) {
            return $this->sendError('', ['Verify your adress email.'], 403);
        }

        // Generate a reset password token.
        $resetPasswordToken = str_pad(strval(random_int(1, 9999)), 9, '0', STR_PAD_LEFT);

        // Check if a reset password token already exists.
        if (! $userPassReset = PasswordResetToken::where('email', $user->email)->first()) {
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        } else {
            $userPassReset->update([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        }
        // Build the reset password link: get domain server to send email link.
        $resetPasswordLink = env('http://localhost:3000/')."/change-password?token=$resetPasswordToken&email=$user->email";

        // Send an email with the reset password link.
        Mail::to($user->email)
            ->send(new PasswordResetNotification($user, $resetPasswordLink, $resetPasswordToken));

        // Return the response indicating that the email has been sent.
        return $this->sendResponse('', 'Email is send');
    }

    /**
     * Method to reset the password.
     */
    /*public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // Validate the reset password form inputs.
        $inputs = $request->validated();

        // Retrieve the user associated with the specified email address.
        $user = User::where('email', $inputs['email'])->firstOrFail();

        // Retrieve the reset password token associated with the user.
        $resetPassword = PasswordResetToken::where('email', $user->email)->first();

        // Check the validity of the reset password token.
        if (! $resetPassword || $resetPassword->token !== $request->token) {
            return $this->sendError('', ['Please, try again'],  401);
        }

        // Update the user's password.
        $user->fill([
            'password' => Hash::make($inputs['password']),
        ]);
        $user->save();

        // Delete the user's previous authentication token.
        $user->token()->delete();
        // Delete the reset password token.
        $resetPassword->delete();

        // Generate a new authentication token for the user.
        $token = $user->createToken('auth_token')->plainTextToken;

        // Send an email confirming the password reset.
        Mail::to($user->email)
            ->send(new confirmPasswordReset());

        // Return the response indicating that the password has been reset successfully.
        return $this->sendResponse('', 'Password reset successfully');
    }*/


    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            // Validate the reset password form inputs.
            $inputs = $request->validated();

            // Retrieve the user associated with the specified email address.
            $user = User::where('email', $inputs['email'])->firstOrFail();

            // Retrieve the reset password token associated with the user.
            $resetPassword = PasswordResetToken::where('email', $user->email)->first();

            // Check the validity of the reset password token.
            if (!$resetPassword || $resetPassword->token !== $request->token) {
                // If the token is invalid, return an error response.
                return $this->sendError('Invalid Token', ['Please, try again'], 401);
            }

            // Update the user's password.
            $user->fill([
                'password' => Hash::make($inputs['password']),
            ]);
            $user->save();

            // Delete the user's previous authentication token.
            $user->tokens()->delete();

            // Delete the reset password token.
            $resetPassword->delete();

            // Generate a new authentication token for the user.
            $token = $user->createToken('auth_token')->plainTextToken;

            // Send an email confirming the password reset.
            Mail::to($user->email)
                ->send(new ConfirmPasswordReset());

            // Return the response indicating that the password has been reset successfully.
            return $this->sendResponse(['token' => $token], 'Password reset successfully');
        } catch (Exception $e) {
            // Handle exceptions and return an error response.
            return $this->sendError('Internal Server Error', ['An error occurred while processing your request.'], 500);
        }
    }



    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        // Retrieve the currently authenticated user using the Sanctum authentication guard.
        $user = auth('sanctum')->user();

        // Update the user model with the validated data from the ChangePasswordRequest.
        $user->fill($request->validated());

        // Save the changes to the user model in the database.
        $user->save();

        // Return the response indicating that the password has been changed successfully.
        return $this->sendResponse(null, 'Password has changed');
    }

}
