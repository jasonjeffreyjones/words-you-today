# Words You Today Status

## Now

- Today we are working toward a version 2.0. We want to add features, fix issues and get to a stable version that we push to GitHub and the production website.
- We must add a Skip feature to wyt.php. Rather than respond Yes or No, the user can swipe down or tap a small button to Skip. When the user chooses to Skip, nothing is recorded to the database. The interface moves to the next signifier.
- In navigation, add Fun after My Stats. Fun goes to an index page of links at https://jasonjones.ninja/words-you-today/fun/. The first link says "Pearlin Scale."  The link goes to fun/pearlin.php. On that page, just place the content for now is Coming soon... within the Words You Today template.

## Next

- Show the user encouraging momentum feedback.  For instance: Wow, you have logged in for XX days in a row!  You completed 10 signifiers.  Try for 25 today.  Keep going!

## Later

- Create a slide show of the same spirit as "Spotify Wrapped" for users who meet the 30 or more 10+ signifiers days out of the last 60 calendar days.
- Brainstorm ways to make the app more visually distinct.
- Add sign-in with Google functionality.
- Users can Boost or Slow a signifier.  Boosted signifiers are more likely to be seen; they have four chances for every one when signifiers are chosen.  Slowed signifiers are the opposite; if they are randomly chosen, there is a 1/4 chance the signifier is shown and a 3/4 chance the process tries again.  Users may only Boost or Slow a total of 20 signifiers.  There is a page where they may inspect and edit their list.  The page briefly explains how Boosting and Slowing works.
- Implement Fun features.

## Done

- Secured the forgot/reset password flow. Reset links are delivered only by authenticated SMTP, requests use generic responses and rate limiting, tokens are hashed, expiring and single-use, and a successful reset invalidates only that user's existing sessions.
- On My Stats, show a progress bar toward the next 1000 total responses Milestone.  Milestones are 1000, 2000, 3000 and 4000+.  The user is working towards the next milestone.  Use Standard Emojis wrapped in a styled span to show users which Milestones they have already met.
Milestone	Emoji Representation	CSS Suggestion
1000	🥉 (Bronze Medal)	filter: drop-shadow(0 0 5px #cd7f32);
2000	🥈 (Silver Medal)	filter: drop-shadow(0 0 5px #c0c0c0);
3000	🥇 (Gold Medal)	filter: drop-shadow(0 0 8px #ffd700);
4000+	💎 (Diamond)	animation: pulse 2s infinite;
- Minimal Viable Product built and launched to production in just a day and a half!
- Version 1.1 completed.
- Indefinite login until intentional logout.
- Account email change.
- Account password change.
- Forgot/reset password flow.
- Clearer distinction between login and create-account screens.
- Personal CSV data export from stats.php.

## Known Risks

- Production PHP is on version 7.2, so future PHP code must stay compatible with that baseline unless hosting changes.
