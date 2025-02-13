Using domain models
^^^^^^^^^^^^^^^^^^^

This extension already provides ready-to-use domain model and data
mapper classes for the following:

- back-end users

- back-end user groups

- front-end users

- front-end user groups

- countries (readonly)

- languages (readonly)

- currencies (readonly)


Models
""""""

All models need to inherit from AbstractModel and be located in the
Model/ directory of your extension. Have a look at the front-end user
model in oelib as an example:

::

   class FrontEndUser extends AbstractModel implements MailRole, Address
   {
         /**
        * Gets this user's user name.
        *
        * @return string this user's user name, will not be empty for valid users
        */
         public function getUserName() {
                 return $this->getAsString('username');
       }

         /**
        * Gets this user's user groups.
        *
        * @return Collection this user's FE user groups, will not be empty if
        *                       the user data is valid
        */
         public function getUserGroups() {
                 return $this->getAsList('usergroup');
       }
   }

For accessing the model data, you need to use the getAs\*/setAs\*
functions so the type checking/conversion and lazy loading works.
Using member variables directly will circumvent this and is not
recommended.

That’s pretty much all you need to know how to build your own models.
Please read the other provided model classes for examples.


Writing data mappers
""""""""""""""""""""

Data mappers allow reading models from the database and writing them
again. Relations are also automatically handled (the relation details
are read from TCA). Please have a look at the provided data mappers
for examples:

::

   class FrontEndUserMapper extends AbstractDataMapper {
         /**
        * @var string the name of the database table for this mapper
        */
         protected $tableName = 'fe_users';

         /**
        * @var class-string<M> the model class name for this mapper, must not be empty
        */
         protected $modelClassName = FrontEndUser::class;

         /**
        * @var array the (possible) relations of the created models in the format
        *            DB column name => mapper name
        */
         protected $relations = array(
                 'usergroup' => FrontEndUserGroupMapper::class,
       );
   }

Please note that you explicitly need to list all relations that should
be supported (because you might want to omit some unneeded relations
for performance reasons). For all relations, you also need to write
the corresponding getters in the model class.


Using data mappers
""""""""""""""""""

To ensure object identity, you need to access all data mappers through
the mapper registry:

::

   $realtyObject = tx_oelib_MapperRegistry
      ::get('tx_realty_Mapper_RealtyObject')->find($this->getUid());

A model that is retrieved through a data mapper can have several
states:

- **virgin:** has neither any data nor a UID yet

- **ghost:** already has a UID (which is not checked yet to actually
  exist in the DB), but the data has not been (lazily) loaded from the
  DB yet; will be loaded the first time a data item is accessed

- **loading:** the data currently is being loaded from the DB; this is a
  transient state between *ghost* and *loaded/dead*

- **loaded:** the model’s data has been successfully loaded from the DB

- **dead:** the data mapper has tried to load this model from the DB,
  but has failed (because there is not record with that UID in the DB)

When working with mappers and model in unit tests, you can often skip
accessing the DB by using ghosts (when only a UID is needed and the
model’s data is never expected to be loaded or saved):

::

   $realtyObject = MapperRegistry::get('tx_realty_Mapper_RealtyObject')
       ->getNewGhost();

If you need a model with data and you’re not testing any m:n or 1:n
relations, you can fill the model with data:

::

   $realtyObject = MapperRegistry::get('tx_realty_Mapper_RealtyObject')
       ->getLoadedTestingModel(array(‘title’ => ‘nice house’, ‘city’ => $cityUid));


Mocking a data mapper
"""""""""""""""""""""

The mapper registry class provides a public function which you can use
to pre-set a particular mapper. You can even replace it with a mock
mapper:

::

   $mapper = $this->getMock(
         'tx_realty_Mapper_District', array('findAllByCityUidOrUnassigned')
   );
   $mapper->expects($this->once())
         ->method('findAllByCityUidOrUnassigned')->with(42)
         ->will($this->returnValue($cities));
   tx_oelib_MapperRegistry::set('tx_realty_Mapper_District', $mapper);
