<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<form action="signup-bdd" method="POST" class="space-y-4">
        {{csrf_field()}}
           
          <input type="nom" name="nom" placeholder="Nom" required class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="prenom" name="prenom" placeholder="Prenom" required class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="telephone" name="tel" placeholder="Telephone" required class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <input type="email" name="email" placeholder="Email" required class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <input type="password" name="mdp" placeholder="Password" required class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

           

       

          
            <div class="flex items-center">
                <input type="checkbox" id="terms" name="terms" class="mr-2">
                <label for="terms" class="text-gray-600 text-sm">I accept the <a href="#" class="text-blue-500 underline">terms and conditions</a></label>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-md font-semibold hover:bg-blue-600">Sign Up</button>
        </form>


</body>
</html>