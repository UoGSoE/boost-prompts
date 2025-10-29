## Developer Team Guidelines

The developer team is very small - just four people.  So we have developed some guidelines to help us work together.

### Code Style

We follow the laravel conventions for code style and use `pint` to enforce them.

We keep our code *simple* and *readable* and we try to avoid complex or clever code.

We like our code to be able to be read aloud and make sense to a business stakeholder (where possible).

We like readable helper methods and laravel policies to help keep code simple and readable.  For example:

@verbatim
   ```php
   // avoid code like this
   if ($user->is_admin && $user->id == $project->user_id) {
       // do something
   }

   // prefer this
   if ($user->can('edit', $project)) {
       // do something
   }
   ```
@endverbatim

We **never** use raw SQL or the DB facade in our code.  We **always** use the eloquent ORM and relationships.

Our applications are important but do not contain a lot of data.  So we do not worry too much about micro-optimizations of database queries.  In 99.9999% of cases doing something like `User::orderBy('surname')->get()` is fine - no need to filter/select on specific columns just to save a 100th of a millisecond.

We like early returns and guard clauses.  Avoid nesting if statements or using `else` whereever possible.

### Seeding data for local development

When developing locally, we use a seeder called 'TestDataSeeder' to seed the database with data.  This avoids any potential issues with running laravel's default seeder by accident.

So if you have created/modified a model or factory, please check that seeder file matches your changes.

### Eloquent model class conventions

We have a rough convention for the order of functionality in our Eloquent models.  This is :

1. Model boilerplate (eg, the $fillable array)
2. Lifecycle methods (eg, using the booted method to do some extra work)
3. Relationships
4. Scopes
5. Accessors/Mutators
6. Custom methods

This convention makes it much easier to navigate the code and find the methods you are looking for.

Also note that we like 'fat models' - helper methods, methods that make the main logic read more naturally - are all fine to put on the model.  Do not abstract to service classes without checking with the user first.  And if there are not existing service classes in the application **NEVER** introduce them unless given explicit permission by the user.

### Livewire component class conventions

Our conventions for livewire components are:

1. Properties and attributes at the top
1.1. Any properties which are used as filters/search parameters in the component should use the `#[Url]` livewire attribute
2. The mount() method followed by the render() method
3. Any lifecycle methods (such as updatedFoo()) next
4. Any custom methods after all that.

### Testing style

We like feature tests and rarely write unit.

We always test the full side-effects and happy/unhappy paths of our code.  For example, a call to a method that will create a new record and send an email notification if validation passes - we would make sure in the test that if invalid data is passed we do not create the record or send the email.  Not just test that we got a validation error.

We also test that our code does not do other things that we did not expect it to do - for example, if we are testing a method which deletes a record, we would test that just that one record was deleted, not the whole collection.

We always test the existence of records using the related Eloquent model - not just doing raw database assertions.  This helps catch cases where a relation is doing some extra work or should have had a side-effect.

We like our tests to be readable and easy to understand.  We always follow the 'Arrange, Act, Assert' pattern.

We like to use helpful variable names in tests.  For example we might have '$userWithProject' and '$userWithoutProject' to help us understand what is going on in the assertions.

When writing tests and you are getting unexpected results with assertSee or assertDontSee - consider that it might be that Laravels exception page is showing the values in the stack trace or contextual debug into.  Do a quick sanity check using an assertStatus() call or assertHasNoErrors().  If that doesn't help **ask the user for help**.  They can visit the page in the browser and tell you exactly what is happening and even provide you a screenshot.

### UI styling

We use the FluxUI component library for our UI and Livewire/AlpineJS for interactivity.

Always check with the laravel boost MCP tool for flux documentation.

Do not add css classes to components for visual styleing - only for spacing/alignment/positioning.  Flux has it's own styling so anything that is added will make the component look out of place.  Follow the flux conventions.  Again - the laravel boost tool is your helper here.

Flux uses tailwindcss for styling and also uses it's css reset.  Make sure that anything 'clickable' has a cursor-pointer class added to it.

Always use the appropriate flux components instead of just <p> and <a> tags. Eg:

@verbatim
   ```blade
   <flux:text>Hello</flux:text>

   <flux:link :href="route('home')">Home</flux:link>
   ```
@endverbatim

### If in doubt...

The user us always happy to help you out.  Ask questions before you add new logic or change existing code.

Most of our applications have been running in production for a long time, so there are all sorts of edge cases, features that were added, then removed, the re-added with a tweak, etc.  Legacy code is a minefield - so lean on the user.

If you are having a problem with a test passing - don't just keep adding code or 'hide' the problem with try/catch etc.  Ask the user for help.  They will 100x prefer to be asked a question and involved in the decision than have lots of new, weird code to debug that might be hiding critical issues.


### The most important thing

Simplicity and readability of the code.  If you read the code and you can't imagine saying it out loud - then we consider it bad code.

### Notes from your past self

• Future-me, read this before you touch the keyboard

  - Start with the most obvious solution that satisfies the spec; don’t add guards, validation, or abstractions unless the user
    explicitly asks.
  - Respect the existing guarantees in the stack (Laravel validation, Blade escaping, etc.)—don’t re-implement or double-check them “just in case.”
  - In **ALL CASES**, simplicity beats “clever” logic every time.
  - If a requirement says “simple,” take it literally. No defensive programming unless requested.
  - For ambiguous cases, ask.  THIS IS CRITICAL TO THE USER.

