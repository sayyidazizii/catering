<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function validatorCustomer(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function validatorMerchant(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function registerCustomer(Request $request)
    {
        $this->validatorCustomer($request->all())->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('customer');

        return redirect($this->redirectTo);
    }

    public function registerMerchant(Request $request)
    {
        $this->validatorMerchant($request->all())->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('merchant');

        Merchant::create([
            'user_id' => $user->id,
            'company_name' => 'Nama Perusahaan Belum Diisi',
            'address' => 'Alamat belum diisi',
            'contact' => '081234567890',
            'location' => 'Semarang',
            'description' => 'Edit Deskripsi ini.',
        ]);

        return redirect($this->redirectTo);
    }

    public function showCustomerRegisterForm()
    {
        return view('auth.register-customer');
    }

    public function showMerchantRegisterForm()
    {
        return view('auth.register-merchant');
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
