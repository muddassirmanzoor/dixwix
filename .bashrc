# .bashrc

if [ -f /etc/bashrc/bashrc ]; then
  . /etc/bashrc/bashrc
fi

if [ -f ~/.python_version  ]; then
  . ~/.python_version
fi

# User specific aliases and functions

umask 007
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
