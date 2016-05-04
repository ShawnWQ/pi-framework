README
====================

Tests for Pi Framework
Each object dependency is injected at constructor or assigned at Initialize phase.

We use MockContainer singleton to access most common dependencies. Those dependencies are created with init method. All dependencies mocked are tested without the use of this. singleton