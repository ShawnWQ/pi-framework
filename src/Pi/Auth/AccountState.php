<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/24/15
 * Time: 1:59 AM
 */

namespace Pi\Auth;


enum AccountState : int {

    EmailNotConfirmed = 1;
    Confirmed = 3;
}