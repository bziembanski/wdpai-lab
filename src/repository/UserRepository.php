<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserProfile.php';
class UserRepository extends Repository
{
    public function getUserById(int $id): ?User{
        $statement = $this->database->connect()->prepare("
            SELECT * FROM public.users WHERE user_id = :id;
        ");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $user= $statement->fetch(PDO::FETCH_ASSOC);

        if($user == false){
            return null;
        }
        return new User(
            $user['user_id'],
            $user['email'],
            $user['password']
        );
    }

    public function getUserByEmail(string $email): ?User{
        $statement = $this->database->connect()->prepare("
            SELECT * FROM public.users WHERE email = :email;
        ");
        $statement->bindParam(':email', $email);
        $statement->execute();

        $user= $statement->fetch(PDO::FETCH_ASSOC);

        if($user == false){
            return null;
        }
        return new User(
            $user['user_id'],
            $user['email'],
            $user['password']
        );
    }
    public function getProfileByEmail(string $email): ?UserProfile{
        $statement = $this->database->connect()->prepare("
            SELECT * FROM user_profile WHERE user_profile.email = :email
        ");
        $statement->bindParam(':email', $email);
        $statement->execute();

        $profile= $statement->fetch(PDO::FETCH_ASSOC);

        if($profile == false){
            return null;
        }
        return new UserProfile(
            $profile['email'],
            $profile['username'],
            $profile['joined'],
            $profile['favourite_game'],
            $profile['avatar'],
            $profile['user_id'],
            $profile['role_name']
        );
    }

    public function getProfileById(int $id): ?UserProfile{
        $statement = $this->database->connect()->prepare("
           SELECT * FROM user_profile WHERE user_profile.user_id = :id
        ");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $profile= $statement->fetch(PDO::FETCH_ASSOC);

        if($profile == false){
            return null;
        }
        return new UserProfile(
            $profile['email'],
            $profile['username'],
            $profile['joined'],
            $profile['favourite_game'],
            $profile['avatar'],
            $profile['user_id'],
            $profile['role_name']
        );
    }

    public function addUserProfile(User $user, UserProfile $profile){
        $statement = $this->database->connect()->prepare("
            INSERT INTO public.users (email, password)
            VALUES (?, ?)
        ");
        $statement->execute([
            $user->getEmail(),
            $user->getPassword()
        ]);
        $statement = $this->database->connect()->prepare("
            INSERT INTO public.profiles (favourite_game, username)
            VALUES (?, ?)
        ");
        $statement->execute([
            "",
            $profile->getUsername()
        ]);
        $statement = $this->database->connect()->prepare("
            SELECT * FROM public.profiles WHERE username = :username;
        ");
        $username= $profile->getUsername();
        $statement->bindParam(':username', $username);
        $statement->execute();
        $newprofile= $statement->fetch(PDO::FETCH_ASSOC);

        $statement = $this->database->connect()->prepare("
            UPDATE public.users SET profile_id=? WHERE email=?;
        ");
        $statement->execute([
            $newprofile['profile_id'],
            $user->getEmail()
        ]);
    }

    public function editProfile(int $user_id, string $username, string $favouriteGame, string $avatar){
        $stmt = $this->database->connect()->prepare("
            SELECT profile_id FROM users WHERE user_id = :user_id;
        ");
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $profile_id = $stmt->fetch(PDO::FETCH_ASSOC)["profile_id"];
        $stmt = $this->database->connect()->prepare("
            UPDATE profiles SET username=:username, favourite_game=:favouriteGame, avatar=:avatar WHERE profile_id=:profile_id;
        ");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":favouriteGame", $favouriteGame);
        $stmt->bindParam(":avatar", $avatar);
        $stmt->bindParam(":profile_id", $profile_id);
        $stmt->execute();
    }
}