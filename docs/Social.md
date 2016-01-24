# Social Integration

For now i'm only implementing a provider to like and publish on Facebook, Google and Twitter through SocialAbstractProvider

### Domain

Mongo documents will have a reference to social shared content. For example

Article {
	id: 1,
	title: 'article title',
	social: [
		{ svc: 'facebook', type: 'post', id: 1},
		{ svc: 'facebook', type: 'picture': id: 3},
		{ svc: 'google', type: 'post', id: 1}
	]
}


The idea is to keep track where the content is shared amount social networks.

The publishing action may be triggred by the POST that create the document passing **publishSocial={facebook,google,twitter}&publishType=post**