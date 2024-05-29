import type { Metadata } from "next";
import { Inter } from "next/font/google";
import AuthenticationWrapper from "@/components/AuthenticationWrapper";

import { Shell } from "@/components/Shell/Shell";
import "@mantine/charts/styles.css";
import { ColorSchemeScript, createTheme, MantineProvider } from "@mantine/core";
import "@mantine/core/styles.css";
import { Notifications } from "@mantine/notifications";
import "@mantine/notifications/styles.css";
import { UserProvider } from "@/components/Contexts/UserContext";
import { DeviceProvider } from "@/components/Contexts/DeviceContext";

const myTheme = createTheme({
  primaryColor: "blue",
  defaultRadius: 0,
});

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Air Quality - App",
  description: "Air Quality Monitoring System.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <head>
        <ColorSchemeScript defaultColorScheme="dark" />
      </head>
      <body className={inter.className}>
        <MantineProvider defaultColorScheme="dark" theme={myTheme}>
          <Notifications />
          <UserProvider>
            <DeviceProvider>
              <AuthenticationWrapper>
                <Shell>{children}</Shell>
              </AuthenticationWrapper>
            </DeviceProvider>
          </UserProvider>
        </MantineProvider>
      </body>
    </html>
  );
}
