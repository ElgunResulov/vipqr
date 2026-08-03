<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin');
        }

        $this->render('admin/auth/login', [
            'title' => 'Admin giriş',
        ], 'admin/layouts/guest');
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $attempts = (int) ($_SESSION['_login_attempts'] ?? 0);
        $lockedUntil = (int) ($_SESSION['_login_locked_until'] ?? 0);
        if ($lockedUntil > time()) {
            flash('error', 'Çox sayda uğursuz cəhd. Bir az sonra yenidən yoxlayın.');
            $this->redirect('/admin/login');
        }

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        store_old(['username' => $username]);

        if ($username === '' || $password === '') {
            flash('error', 'İstifadəçi adı və şifrə tələb olunur.');
            $this->redirect('/admin/login');
        }

        if (!Auth::attempt($username, $password)) {
            $attempts++;
            $_SESSION['_login_attempts'] = $attempts;
            if ($attempts >= 5) {
                $_SESSION['_login_locked_until'] = time() + 60;
                $_SESSION['_login_attempts'] = 0;
                flash('error', 'Çox sayda uğursuz cəhd. 1 dəqiqə gözləyin.');
            } else {
                flash('error', 'Giriş məlumatları yanlışdır.');
            }
            $this->redirect('/admin/login');
        }

        unset($_SESSION['_login_attempts'], $_SESSION['_login_locked_until']);
        clear_old();
        flash('success', 'Xoş gəldiniz!');
        $this->redirect('/admin');
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        Auth::logout();
        flash('success', 'Çıxış edildi.');
        $this->redirect('/admin/login');
    }
}
