{
  description = "Laravel NDI 2025 Development Environment";

  inputs = {
    # PHP 8.4 is very new, so we use the unstable branch
    nixpkgs.url = "github:nixos/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs { inherit system; };

        # 1. Define PHP 8.4 with specific extensions
        # Equivalent to: FROM php:8.4-fpm + docker-php-ext-install ...
        myPhp = pkgs.php84.buildEnv {
          extensions = ({ enabled, all }: enabled ++ (with all; [
            pdo
            pdo_mysql
            zip
            gd
            # 'gd' in Nixpkgs is typically compiled with freetype/jpeg support by default
          ]));
          
          # Optional: Add php.ini configuration here
          extraConfig = ''
            memory_limit = 512M
            upload_max_filesize = 100M
            post_max_size = 100M
          '';
        };

      in
      {
        # The Development Shell
        # Equivalent to the build environment in the Dockerfile
        devShells.default = pkgs.mkShell {
          # Tools available in the shell
          buildInputs = [
            # PHP and Composer
            myPhp
            pkgs.php84Packages.composer

            # System Dependencies (apt-get install ...)
            pkgs.git
            pkgs.unzip
            pkgs.zip
            pkgs.curl
            pkgs.mariadb.client # Replaces default-mysql-client
            
            # Libraries (libpng, libzip, etc. are implicitly linked by the PHP extensions,
            # but we include them if you need to compile C-libraries manually)
            pkgs.libzip
            pkgs.libpng
            pkgs.libjpeg
            pkgs.freetype
          ];
        };
      }
    );
}
