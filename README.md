# dockingplugin

### Local Requirements
----
##### XAMPP / WAMP for localhost development.

##### GIT Toolset
Windows - https://git-scm.com/download/win

Mac - https://git-scm.com/download/mac

When installing GIT, be sure that the Add to Path AND Add to Context are checked for easier access. It adds options in the context menu when you right click a folder. This keeps you from having to cd through directories in the command terminal to the plugin directory.

### Cloning the Plugin
----
In your localhost wordpress instance. Find the plugins folder and right click on it. It should have an option to Git Bash Here. This will open the terminal at the plugins folder and is ready for you to clone the repository.

Run `git clone http://github.com/ORourkeHospitality/dockingplugin.git`

This will automatically create the folder named `dockingplugin` inside of the plugins folder and download all files from the repository.

### Making Changes
----
In the same terminal you have open from cloning the repository. Run `cd dockingplugin` This puts you in the plugins directory. If you use atom as your text editor you can run `atom .` and it will open the current terminal directory in atom. If using another editor, you may need to manually open the directory.

Hint: In terminals, cd stands for Change Directory. To go backwards you can do `cd .` or go backwards twice `cd ..`

### Commiting your Changes
----
After making any changes, a blank space even, you can run `git status` to see which files have changed. When you're ready to commit the changes. The fastest way in terminal is to run the following sequence.

`git add .` <- adds all edited files to the commit

`git commit -m "added the blank space"` <- -m is the parameter for message

`git push origin master` <- pushes the code back up to it's landing or server
