<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Mail\ConfirmPasswordReset;
use App\Mail\PasswordResetNotification;
use App\Models\Api\V1\PasswordResetToken;
use App\Models\Api\V1\Role;
use App\Models\Api\V1\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function user(): string
    {
        return 'Authenticated user';
    }

    /**
     * @param Request $request
     * @return User
     */
    public function register(Request $request):JsonResponse
    {
        try {
            // validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string',
                ]);

            $validated = $validateUser->validated();

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $roleId = Role::where('name', 'subscriber')->first()->id;
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->role()->associate($roleId);
            $user-> save();

            return response()->json([
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Login the user
     * @param Request $request
     * @return User
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // validated
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken("API TOKEN OF " . $user->name)->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'message' => 'You are sign in!'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 200);
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
