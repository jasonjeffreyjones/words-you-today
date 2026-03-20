Together, you and I are building a web app.
The app is named Words You Today.
I am a web developer experienced with using HTML, CSS, Bootstrap, PHP, Python and databases.
I am comfortable with basic Linux server stuff like SSH, SFTP and cron.

Here is the founding statement for the app: "Founding Statement for  Words You Today
I am a scientist who studies the human self.  I need data.  I want to build an app where people feel comfortable sharing that data.
I want individuals to use the app every day for their entire adult life.
The value to the user must be real.
It comes in the form of greater self-knowledge.
Self-actualization is the ideal.
Users will contribute to scientific understanding of the human condition.
The cost to the user will be low or zero.
The user is not the product.
There will be no ads ever.
An anonymized dataset will be freely available for research.
Individuals' data will never be sold.
The code that runs the app will be open-source.
I commit to these statements, and should be held accountable for any breach."

In the main interface, the user is shown one identity signifier and asked to endorse it or not.  In other words, the user is asked "Does `signifier` describe you today?"
They swipe to answer Yes or No.
Then another signifier is presented.

After providng some signifier resposonses, users can explore their data and compare it to the aggregate data.  For example: which signifiers do I endorse that most users don't?  Which combination of two endorsed signifiers is most unusual?

Right now I can foresee several main pieces of the structure.
1) Home contains a welcome message.  Greet the user if they are logged in.  A brief explanation of how the app works.  A big, inviting Start button that sends the user to the main WYT interface.
2) WYT contains the main interface.
In the main interface, the user will be shown one identity signifier and asked to endorse it or not.  In other words, the user is asked "Does <signifier> describe you today?"
They swipe to answer Yes or No.  Swipe left (i.e. right-to-left) to reject.  Swipe right (i.e. left-to-right) to endorse.  There are button options also.
A feature for later is Skip.  The user may swipe down to Skip, and nothing is recorded in the database.
Then another signifier is presented.
The database contains a list of identity signifiers.  The list includes words, phrases and emojis.
Signifiers are chosenly randomly.  We will choose a random permutation of the identity signifiers for each user each day.  A user should not see a repeat within a day.
Eventually, I think we need to batch/cache responses instead of making a database write for every swipe.  Let's keep it simple to start, but keep database load in mind.
3) My Stats contains a page of numbers that will be interesting to the user.  For example, their total number of signifiers swiped and their Yes percentage.
4) Fun contains links to (later-to-implemented) features only available to Paid users.  Matchy-Matchy allows users to invite one other existing user to compare data.  Predicted Probabilities is a feature a Paid user can turn on; it shows the predicted probability of Yes after each response on the main WYT interface.
5) Account settings contains typical account things: signup, login, forgot password, change password.

The URL to use Words You Today is https://jasonjones.ninja/words-you-today/
The public GitHub directory for the source is https://github.com/jasonjeffreyjones/words-you-today
Always use https within jasonjones.ninja. Never add www. in front of jasonjones.ninja
