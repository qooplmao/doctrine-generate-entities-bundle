doctrine-generate-entities-bundle
=================================

This is just a simple addition to the entity generator featured in the Doctrine Bundle for Symfony2.

It works the same way as in the command is the same except for a few small caveats..

- It creates an Interface (which contains all of the doc blocks)
- It replaces all doc blocks in the entity with {@inheritdoc} (as they are in the interface)
- It inplments the paired Interface
- It has a "has" method for oneToMany or manyToMany associations
- It uses the "has" check in the "add" and "remove" methods

One major thing is that it auto generates an add/remove like so..

```
class File
{

...

public function addSomething(SomethingInterface $something)
{
    if (!$this->hasSomething($something)) {
        $this->somethings->add($something);
        $something->setFile($this);
    }
    
    return $this;
}
```

The `$something->set<entity>` part is hardcoded as if a one to many so if you are doing a many to many (so `add/remove<entity>`) then you will need to change stuff.

Not entirely sure if it does anything else.
It does what I wanted it to do to save me a bit of time every time I first created models/entities for bundles.

I use this with a version of the Sylius\ResourceBundle as a base which uses (or at least I use) ResolveTargetEntities and have noticed that it cocks it up a treat if the entities are being "resolved" before the command is run, so don't do that.

If there is something wrong fix it, I didn't make it in the first place so I won't be upset.


Oh yeah... the command is `qooplmao:generate:entities` rather than `doctrine:generate:entities`.
