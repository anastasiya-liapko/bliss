<?php

namespace App\Controllers\AdminPanel;

use App\Models\Admin;
use Core\Controller;
use Exception;

/**
 * Class DocumentTemplates.
 *
 * @codeCoverageIgnore
 *
 * @package App\Controllers\AdminPanel
 */
abstract class AdminPanel extends Controller
{
    /**
     * The admin.
     *
     * @var Admin
     */
    protected $admin;

    /**
     * Checks if exist the admin id in the session.
     *
     * @return void
     * @throws Exception
     */
    protected function before(): void
    {
        parent::before();

        // Here you can not use HttpFoundation, because the session was start through the admin panel
        // by session_start() function.
        if (isset($_SESSION['user']['id']) && ! empty($_SESSION['user']['id'])) {
            $this->admin = Admin::findById($_SESSION['user']['id']);
        }

        if (empty($this->admin)) {
            throw new Exception('Forbidden.', 403);
        }
    }
}
