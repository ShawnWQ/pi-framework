# Pi ODM

An implementation for document based databases. I'm starting with MongoDB only

The core objectives:
- Map a POCO class to an collection, cleanly by conventions without any attributes required. This is thanks to HHVM typing


## Connection

The MongoConnection is accessed by the user to:
- Get repository for a given IEntity

API
- persistEntity
- getRepository() - DocumentManager->getRepository()


## Repository

The repository has access to DocumentManager
API
- flush
- commit
- find
- delete

## Hydrator

Hydration is the act of populating an object from a set of data. The `Hydrator` is a component to provide mechanisms both for hydrating objects and extracting data from them.

Hydrators are generate per IEntity during build phase. The developer dont have knowledge of them because he'll insert, update and read using the POCO IEntity classes.

When the Hydrator is generated, it's able to set a IEntity object data from a database result - `hydration` - and also extract the data from there - `extracting`.



### Hydrator Factory

The HydratorFactory class is responsible for instantiating a correct hydrator type based on document's meta data.


## Entity

````

namespace CityPortal\Data\Entity;
<<Document("User")>>
class User {
	<<References(get_class(User))>>
	protected int $userId;

	<<Embed(get_class(UserInfo))>>
	protected $userInfo;
}
````

````
{
	id: ObjectId('11111111'),
	userId: {
		_id: ObjectId('00000000'),
		classType: '\CityPortal\Data\Entity\User'
	},
	userInfo: {
		displayName: 'Guilherme Cardoso',
		id: 1
	}
}
````

#### Auto Increment

Mark field as auto increment

#### References

Reference another document. May be the same collection or not. Schema recommended:



#### Embed
