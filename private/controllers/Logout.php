
<?php
/**
 * Logout controller
 */
class Logout extends Controller
{
    function index($id = null)
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
