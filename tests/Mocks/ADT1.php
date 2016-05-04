<?hh


namespace Mocks;

<<InheritanceType('Single'),DiscriminatorField('type')>>
class ADT1 extends ADTBase {
  protected $type;
}
