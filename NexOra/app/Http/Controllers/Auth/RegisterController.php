<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   use RegistersUsers;

    /**
     * Redirect after registration.
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration manually.
     *
     * Members are logged in immediately.
     * Managers are NOT logged in until their workspace is approved.
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        $validated = $validator->validate();

        $user = $this->create($validated);

        if ($user->role === 'member') {
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return redirect()
            ->route('login')
            ->with(
                'status',
                'Your workspace application has been submitted successfully. You can log in after admin approval.'
            );
    }

    /**
     * Validation rules.
     */
    protected function validator(array $data)
    {
        $rules = [
            'account_type' => [
                'required',
                'in:member,spaceManager'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];

        // Additional validation for Workspace Managers
        if (($data['account_type'] ?? null) === 'spaceManager') {

            $rules = array_merge($rules, [

                'workspace.name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'workspace.location' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'workspace.description' => [
                    'nullable',
                    'string',
                    'max:5000'
                ],

                'workspace.facilities' => [
                    'nullable',
                    'array'
                ],

                'workspace.facilities.*' => [
                    'string',
                    'max:100'
                ],

                'workspace.custom_facilities' => [
                    'nullable',
                    'string',
                    'max:1000'
                ],

                'workspace_images' => [
                    'nullable',
                    'array'
                ],

                'workspace_images.*' => [
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],

                'rooms' => [
                    'required',
                    'array',
                    'min:1'
                ],

                'rooms.*.name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'rooms.*.room_type' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'rooms.*.capacity' => [
                    'required',
                    'integer',
                    'min:1'
                ],

                'rooms.*.hourly_price' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'rooms.*.facilities' => [
                    'nullable',
                    'array'
                ],

                'rooms.*.facilities.*' => [
                    'string',
                    'max:100'
                ],

                'rooms.*.custom_facilities' => [
                    'nullable',
                    'string',
                    'max:1000'
                ],

                'rooms.*.images' => [
                    'nullable',
                    'array'
                ],

                'rooms.*.images.*' => [
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],
            ]);
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create user and, if manager, workspace and rooms.
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $isManager = $data['account_type'] === 'spaceManager';

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $isManager ? 'spaceManager' : 'member',
                'is_active' => true,
            ]);

            // Member registration ends here
            if (!$isManager) {
                return $user;
            }

            // Prepare workspace facilities
            $workspaceFacilities = $this->prepareFacilities(
                $data['workspace']['facilities'] ?? [],
                $data['workspace']['custom_facilities'] ?? ''
            );

            // Create workspace
            $workspace = Workspace::create([
                'manager_id' => $user->id,
                'name' => $data['workspace']['name'],
                'location' => $data['workspace']['location'],
                'description' => $data['workspace']['description'] ?? null,
                'facilities' => $workspaceFacilities,
                'approval_status' => 'pending',
                'is_active' => true,
            ]);

            // Upload workspace images
            $workspaceImages = request()->file('workspace_images', []);

            foreach ($workspaceImages as $index => $image) {

                $path = $image->store('workspaces', 'public');

                $workspace->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }

            // Create rooms
            foreach ($data['rooms'] as $index => $roomData) {

                $roomFacilities = $this->prepareFacilities(
                    $roomData['facilities'] ?? [],
                    $roomData['custom_facilities'] ?? ''
                );

                $room = $workspace->rooms()->create([
                    'name' => $roomData['name'],
                    'room_type' => $roomData['room_type'],
                    'capacity' => $roomData['capacity'],
                    'hourly_price' => $roomData['hourly_price'],
                    'facilities' => $roomFacilities,
                    'is_active' => true,
                ]);

                // Upload room images
                $roomImages = request()->file(
                    "rooms.$index.images",
                    []
                );

                foreach ($roomImages as $imageIndex => $image) {

                    $path = $image->store('rooms', 'public');

                    $room->images()->create([
                        'image_path' => $path,
                        'is_primary' => $imageIndex === 0,
                        'sort_order' => $imageIndex,
                    ]);
                }
            }

            return $user;
        });
    }

    /**
     * Combine predefined facilities with custom facilities.
     */
    private function prepareFacilities(array $selected, string $custom): array
    {
        $customFacilities = explode(',', $custom);

        $customFacilities = array_map(
            fn ($facility) => trim($facility),
            $customFacilities
        );

        $facilities = array_merge($selected, $customFacilities);

        $facilities = array_filter($facilities, function ($facility) {
            return $facility !== '';
        });

        return array_values(array_unique($facilities));
    }
}
