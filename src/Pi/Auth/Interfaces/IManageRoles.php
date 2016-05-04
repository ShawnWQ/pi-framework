<?hh

namespace Pi\Interfaces;

interface IManageRoles {

  public function getRoles($userAuthId) : array;

  public function getPermissions($userAuthId) : array;

  public function hasRole($userAuthId, string $role);

  public function hasPermission($userAuthId, string $role);


  public function assignRoles($userAuthId, ?array $roles = null, ?array $permissions = null);

  public function unAssignRoles($userAuthId, ?array $roles = null, ?array $permissions = null);

}
