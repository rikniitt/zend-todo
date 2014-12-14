# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # Root password for mysql installed in provision.
  MYSQL_ROOT_PASSWORD = "secret"

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "hashicorp/precise32"
  
  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:13000" will access port 80 on the guest machine.
  config.vm.network "forwarded_port", guest: 80, host: 13000

  # Enable provisioning with custom shell script.
  config.vm.provision "shell", path: "vagrant_scripts/provision.sh", args: MYSQL_ROOT_PASSWORD

  # Run custom shell script to install project.
  config.vm.provision "shell", path: "vagrant_scripts/install.sh", args: MYSQL_ROOT_PASSWORD, privileged: false
end
