<?php
namespace App\Filters;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{

    /**
     * @param RequestInterface $request
     * @param $arguments
     * @return RedirectResponse|ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session  = session();
        $now      = date(DATETIME_FORMAT_DB);
        $language = service('language');
        // 1) Check login
        if (! $session->get('isLoggedIn')) {
            // Save intended URL so you can redirect after login
            $lang = $session->get('lang');
            $session->set('redirect_url', current_url(true)->__toString());
            if (!empty($lang)) {
                $session->set('lang', $lang);
                $language->setLocale($lang);
            }
            if ($request->getMethod() === 'post') {
                return Services::response()
                    ->setJSON(['status'  => STATUS_RESPONSE_ERR, 'message' => 'Unauthorized'])
                    ->setStatusCode(401);
            }
            return redirect()->to('/')
                ->with('error', lang('System.response-msg.error.not-logged-in'));
        }
        // 2) Check hard session expiry
        if ($now > $session->get('sessionExpiry')) {
            $lang = $session->get('lang');
            $session->destroy();
            if (!empty($lang)) {
                $session->set('lang', $lang);
                $language->setLocale($lang);
            }
            if ($request->getMethod() === 'post') {
                return Services::response()
                    ->setJSON(['status'  => STATUS_RESPONSE_ERR, 'message' => 'Unauthorized'])
                    ->setStatusCode(401);
            }
            return redirect()->to('/')
                ->with('lang', $lang)
                ->with('error', lang('System.response-msg.error.session-expired'));
        }
        // (Optional) 3) Idle timeout, if you want it
        // $lastActive = (int) $session->get('last_active');
        // $maxIdle    = 60 * 60 * 4; // e.g. 4 hours
        // if ($lastActive > 0 && (time() - $lastActive) > $maxIdle) {
        //     $session->destroy();
        //     return redirect()->to('/login')
        //         ->with('error', 'You were idle for too long. Please log in again.');
        // }
        $session->set('lastActive', $now);
        // No return = request continues
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Usually nothing here for auth; you *could* update last_active here instead.
    }
}