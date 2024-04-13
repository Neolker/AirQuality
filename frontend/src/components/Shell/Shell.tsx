"use client";

import { AppShell, Burger, Group } from "@mantine/core";
import { useDisclosure } from "@mantine/hooks";
import {
  IconBellRinging,
  IconBrandAppleArcade,
  IconFingerprint,
  IconSettings,
} from "@tabler/icons-react";
import { useState } from "react";
import classes from "./Shell.module.css";

const data = [
  { link: "", label: "Devices", icon: IconBrandAppleArcade },
  { link: "", label: "Notifications", icon: IconBellRinging },
  { link: "", label: "Authentication", icon: IconFingerprint },
  { link: "", label: "Settings", icon: IconSettings },
];

export function Shell({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const [mobileOpened, { toggle: toggleMobile }] = useDisclosure();
  const [desktopOpened, { toggle: toggleDesktop }] = useDisclosure(true);
  const [active, setActive] = useState("Devices");

  const links = data.map((item) => (
    <a
      className={classes.link}
      data-active={item.label === active || undefined}
      href={item.link}
      key={item.label}
      onClick={(event) => {
        event.preventDefault();
        setActive(item.label);
      }}
    >
      <item.icon className={classes.linkIcon} stroke={1.5} />
      <span>{item.label}</span>
    </a>
  ));

  return (
    <AppShell
      header={{ height: 60 }}
      navbar={{
        width: 300,
        breakpoint: "sm",
        collapsed: { mobile: !mobileOpened, desktop: !desktopOpened },
      }}
      padding="md"
    >
      <AppShell.Header>
        <Group h="100%" px="md">
          <Burger
            opened={mobileOpened}
            onClick={toggleMobile}
            hiddenFrom="sm"
            size="sm"
          />
          <Burger
            opened={desktopOpened}
            onClick={toggleDesktop}
            visibleFrom="sm"
            size="sm"
          />
        </Group>
      </AppShell.Header>
      <AppShell.Navbar p="md">{links}</AppShell.Navbar>
      <AppShell.Main>{children}</AppShell.Main>
    </AppShell>
  );
}
