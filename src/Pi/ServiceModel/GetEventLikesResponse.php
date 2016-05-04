<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:47 AM
 */

namespace Pi\ServiceModel;


use Pi\Response;

class GetEventLikesResponse extends Response{

    protected $likes;

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }
}