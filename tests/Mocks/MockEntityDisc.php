<?hh

namespace Mocks;

<<Collection("entity-disc"),InheritanceType('Single'),DiscriminatorField('type'),DefaultDiscriminatorValue('article')>>
Fclass MockEntityDisc extends MockEntity {
	
}