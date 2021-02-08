# System Requirements
- Operating System
- Linux
- Windows
+ PHP Version
+ Minimum: 5.6
+ Recommended: 7.0 (Or Greater)
- Database
- MySQL
* PDO Must be enabled. (Some hosts require you to request this)

# Known Issues
- N/A

# License
CAD-MDT is released under GNU Affero General Public License.
You can view the license terms and conditions at https://www.gnu.org/licenses/agpl-3.0.en.html 

# Installation
- Download the latest version from GitHub.
- Navigate to **sql/new users/**, and import that SQL file on your database.
- Move the contents from the *Upload* folder, into your website directory.
- Navigate to **includes/connect.php**, and open it with a text-editor.
- Change the database information to yours.
- Go to **www.your-site.com/cad-directory/register.php**
- Create an account
- In your database under `users`, Find the newly created account and set the `usergroup` to **Management**
- Done! You now have full access over your CAD/MDT system.
