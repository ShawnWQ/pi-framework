<?hh

namespace Pi\Interfaces;

/**
 * Disposable Interface
 * Used by framework to call dispose on classes implementing it
 */
interface IDisposable {

  /**
   * The function responsable for clean resources and dispose them
   */
  public function dispose();
}
