JAT-Box
======
Authors: Benjamin Stuermer (benjamin.stuermer@pnnl.gov), Tom Schultz (tom.schultz@pnnl.gov), Hunter Stratton (hunter.stratton@pnnl.gov)
July, 2015

The JAT (Just Add Tests) Box is a preconfigured Behat Vagrant virtual machine.


Quick Start
======
1) Install VirtualBox, Vagrant, Putty, and Chrome
2) Navigate to the repo folder in cmd
3) Run vagrant up (starts VM, configures it the first time)
4) Run vagrant ssh (connects to VM with SSH)
5) Once connected, navigate to /vagrant/ (This is the repo folder mounted as a linux share)
6) Run ./selenium/hub (Selenium Grid hub)
  a) Recommended - run this with a terminal multiplexer like screen or tmux
  b) Less recommended - open multiple SSH connections to your VM
7) FROM WINDOWS: run selenium/node from cmd (the hub calls this to fulfill browser requests)
8) FROM SSH: run ./bin/behat


TODO List
=========
- Split the Selenium Grid configuration from the default configuration instead
  of having both in one big file