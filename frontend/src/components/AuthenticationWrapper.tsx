"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Cookies from "js-cookie";
import { Loader } from "@mantine/core";

const AuthenticationWrapper = ({ children }: { children: React.ReactNode }) => {
  const [isLoading, setIsLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    const checkAuth = async () => {
      const sessionToken = Cookies.get("session_id");

      if (!sessionToken) {
        // router.push("/");
        window.location.href = "/" // TODO

      } else {
        setIsLoading(false); // Set loading to false if authenticated
      }
    };

    checkAuth();
  }, [router]);

  if (isLoading) {
    return <Loader color="blue" />; // Show loader while checking authentication
  }

  return <>{children}</>; // Render children if authenticated
};

export default AuthenticationWrapper;
