"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { parseCookies } from "nookies";
import { Loader } from "@mantine/core";

const AuthenticationWrapper = ({ children }: { children: React.ReactNode }) => {
  const [isLoading, setIsLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    const checkAuth = async () => {
      const cookies = parseCookies();
      const session = cookies.user ? JSON.parse(cookies.user)?.session : null;

      if (!session) {
        router.push("/");
        // window.location.href = "/"
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
