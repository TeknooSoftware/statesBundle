#Teknoo Software - States Bundle - Change Log

###[3.0.0-alpha2] - 2016-10-27
###Updated
- Require States 3.0.0-alpha3 at least.
- Code style fix
- Update doc

###[3.0.0-alpha1] - 2016-10-09
###Updated
- Require minimum States 3+ (alpha 1) and States Life Cyclable (alpha 1).

###Removed
- Bundle bootstrapping, useless with States 3+
- Bootstrap service to initialize the States library, not needed with States 3+
- Composer finder service, not needed with States 3+
- Factory and Startup Factory, removed for States 3+
- Removed Integrated proxies for Doctrine's entities and Doctrine's documents, Integrated proxies are removed 
    for States 3+.
- Doctrine Class Meta Listener, not needed with States 3+ to initialize stated class, not needed with States 3+.
- Symfony options to customize the Bundle initialization: services removed. 

##[2.2.3] - 2016-08-23
###Fixed
- Minimum version of teknoo/state, use ^ operator insteadof ~ to allow to use the version 2.1 of States.

##[2.2.2] - 2016-08-04
###Fixed
- Improve optimization on call to native function and optimized

##[2.2.1] - 2016-07-26
###Updated
- Remove legacy reference to Uni Alteri in licences
- Change minimum version about State Life Cyclable exension

###Fixed
- Missing namespace use in IntegratedTrait about document and entity for doctrine's tags

##[2.2.0] - 2016-07-26
###Updated
- Remove deprecated "prototype" scope in Symfony container, to be replace by shared services

###Removed
- Remove Symfony 2.7 support, Symfony 2.8 is needed

##[2.1.1] - 2016-07-26
###Updated
- Fix code style with cs-fixer
- Improve documentation and fix documentations

###Add
- Add the API documentation about the 2.x branch.

##[2.1.0] - 2016-04-16
###Added
- Split States lifecycable support in an optional behavior

###Fixed
- Dependency to States lifecyclable is now not mandatory

##[2.0.3] - 2016-04-09
###Fixed
- Fix code style with cs-fixer

##[2.0.2] - 2016-02-26
###Fixed
- Compatibility with Symfony 3.0 (remove use of factory_method, replaced by factory)

##[2.0.1] - 2016-02-26
###Fixed
- Minimum PHP requirement in composer.json

##[2.0.0] - 2016-02-12
###Updated
- Stable Release, 1.x is switched on legacy branch and next is merged with master.

##[2.0.0-rc6] - 2016-02-03 - Available on the branch "next"
###Updated
- Fix yaml service.yml file

##[2.0.0-rc5] - 2016-02-02 - Available on the branch "next"
###Updated
- Fix composer minimum requirements

##[1.1.2] - 2016-02-02
###Fixed
- Fix composer minimum requirements

##[1.1.1] - 2016-01-27
###Added
- Clean .gitignorefile

##[2.0.0-rc4] - 2016-01-20 - Available on the branch "next"
###Updated
- Clean .gitignore
- Optimizing for inlined internal functions

##[2.0.0-rc3] - 2016-01-19 - Available on the branch "next"
###Updated
- Finalize support of States Lifecyclable extenstion
- Documentation about this extension with Symfony 2.7+

##[2.0.0-rc2] - 2016-01-18 - Available on the branch "next"
###Added
- Support of States Lifecyclable extenstion

###Fixed
- Bug in configuration use token teknoo_states instead of uni_alteri_states

##[2.0.0-rc1] - 2015-12-06 - Available on the branch "next"
###Added
- Release Candidate 1 about 2.0

###Fixed
- Coverage test code

##[1.1.0] - 2015-12-06
###Added
- Stable release 1.1.0

###Remove
- Typo3 class alias loader

##[1.1.0-beta6] - 2015-10-21
###Fixed
- Fix bug to retrieve composer instance via spl functions

##[2.0.0-beta2] - 2015-10-31 - Available on the branch "next"
###Changed
- Migrate from Uni Alteri to Teknoo Software organization

##[1.1.0-beta5] - 2015-10-31
###Fixed
- Migrate from Uni Alteri to Teknoo Software organization

##[2.0.0-beta1] - 2015-09-17 - Available on the branch "next"
###Added
- Support of Mongo
- Split Doctrine ODM (Mongo) and Doctrine ORM entities and factories
 
###Changed
- Build on States 2.x
- Refactoring Factory and Entities
- Refactoring States loader in bundle to use Symfony' components
- Refactoring bundle bootstrap to use container definition 
- Refactoring Doctrine listener for support

##[1.1.0-beta4] - 2015-07-23
###Fixed
- Some tests issues

##[1.1.0-beta3] - 2015-07-19
###Changed
- Split Entity and Document support in two traits

###Added
- Added support of LoaderComposer instead of LoaderStandard in bundle to avoid multiple autoloading mapping

##[1.1.0-beta2] - 2015-07-02
###Added
- Support of Doctrine ODM Proxy

##[1.1.0-beta1] - 2015-06-22
###Changed
- Define a new complementary trait to write easier new integrated proxies.

##[1.0.6] - 2015-06-10
###Added
- Support of States 2.x
- Support of States 1.2

##[1.0.5] - 2015-05-24
###Added
- Support of PHP7 (States is 7x faster than with PHP5.5)
- Add travis file to support IC outside Uni Alteri's server

##[1.0.4] - 2015-05-06
###Fixed
- Documentation

##[1.0.3] - 2015-02-15
###Fixed
- Api Documentation

###Changed
- Update Composer dependencies

##[1.0.2] - 2015-02-09
###Changed
- Documentation updated

###Added
- Contribution rules

##[1.0.1] - 2015-01-28
###Fixed
- Code style

###Changed
- Documentation updated

##[1.0.0] - 2015-01-17
- First stable of the states Bundle

