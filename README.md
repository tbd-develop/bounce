bounce
======

Initially titled Amity Framework on Sourceforge, this codebase has been through a lot of development recently and deserves a new home.

**Upcoming**

I've spent a lot of time recently working out where I want to take the framework. I know that I'm only writing this for me, but that doesn't mean the code shouldn't be clean.

Things I'll be tackling -

+ Controller Inheritance - I don't like inheriting controllers, I would rather a controller be a controller by name, ie. HomeController instead of Home extends Controller. So, we're going to start with that. So convention based naming to identify. 
+ Dependency Injection - Right now, controllers generally inherit from FormDatabaseController (awful naming!) to get access to things like Database, user etc. This goes away when we stop inheritance with controllers, so there has to be an answer. That answer is a DI framework. 
+ Code organization - The code is a cluttered mess right now. All main files are in 'Core', the rest are where they need to be but there's nothing frameworky about the whole thing.
+ Tests - I've long struggled with Unit Tests in PHP. Either it takes an age to set up or the code has to be configured just right. As I've progressed, I've learned that both of those are on me. If I make the code more testable, it will be easier to test and therefore take less time to get set up. 
+ Purpose. 

What is **Purpose**?

I began the framework with the idea of creating a CMS system. It then soon became what is basically a quick and easy way for me to get up and running with web sites. I suppose I have sort of created CMS systems over the years, but I never really developed a generic CMS. So, I have a handful of sites out there based on mainly the same code. I say mainly because I've always tweaked things here and there to fit a purpose. What I want to try and do is break down Bounce into a few component pieces, then reassamble the pieces when I need specific things. There are some things that remain core, such as routing. But anything else is very dependant upon your requirements. 

+ Building a website, you need routing, views, templates, scripts, maybe database, maybe authentication
+ Building an api, you need routing, likely database, maybe authentication

So, the purpose of the framework is going to be everything I need it to be, and hopefully what someone else can use. 
