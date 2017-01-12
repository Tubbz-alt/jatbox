JAT-Box
======
Authors: Benjamin Stuermer (benjamin.stuermer@pnnl.gov), Tom Schultz (tom.schultz@pnnl.gov)

The JAT (Just Add Tests) Box is a preconfigured Behat Vagrant virtual machine.


Quick Start
======
1) Install VirtualBox, Vagrant, and Chrome
2) Navigate to the repo folder in cmd
3) Run vagrant up (starts VM, configures it the first time)
4) Run vagrant ssh (connects to VM with SSH)
5) Once connected, navigate to /vagrant/ (This is the repo folder mounted as a linux share)
6) Run ./selenium/hub (Selenium Grid hub)
7) FROM HOST MACHINE: run selenium/node from terminal (the hub calls this to fulfill browser requests)
8) FROM SSH: run vendor/bin/behat