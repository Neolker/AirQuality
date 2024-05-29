"use client";

import React, {
  createContext,
  useContext,
  useState,
  ReactNode,
  use,
  useEffect,
} from "react";
import { apiCall } from "../utils/apiCall";
import { showNotification } from "@mantine/notifications";

interface Device {
  status: string;
  device_id: string;
  name: string;
  location: string;
  co2_levels: {
    green: number;
    yellow: number;
    red: number;
  };
}

interface DeviceContextType {
  devices: Device[];
  setDevices: React.Dispatch<React.SetStateAction<Device[]>>;
  fetchDevices: () => Promise<void>;
  addDevice: (deviceData: Device) => Promise<void>;
  updateDevice: (deviceData: Device) => Promise<void>;
  deleteDevice: (deviceId: string) => Promise<void>;
}

const DeviceContext = createContext<DeviceContextType | undefined>(undefined);

export const DeviceProvider = ({ children }: { children: ReactNode }) => {
  const [devices, setDevices] = useState<Device[]>([]);

  useEffect(() => {
    fetchDevices();
  }, []);

  const fetchDevices = async () => {
    try {
      const data = await apiCall({
        method: "GET",
        path: "/device/get-list/",
      });
      if (data.status === "OK") {
        console.log(data);
        setDevices(data.device_datas);
        showNotification({
          title: "Devices Loaded",
          message: "All devices have been successfully retrieved.",
          color: "green",
        });
      } else {
        throw new Error("Failed to fetch devices");
      }
    } catch (error: any) {
      showNotification({
        title: "Loading Failed",
        message: error.message,
        color: "red",
      });
    }
  };

  const addDevice = async (deviceData: Device) => {
    try {
      const data = await apiCall({
        method: "POST",
        path: "/device/add/",
        data: deviceData,
      });
      if (data.status === "OK") {
        setDevices((prev) => [...prev, data.device_data]);
        showNotification({
          title: "Device Added",
          message: "The device has been successfully added.",
          color: "green",
        });
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Add Device Failed",
        message: error.message,
        color: "red",
      });
    }
  };

  const updateDevice = async (deviceData: Device) => {
    try {
      const data = await apiCall({
        method: "POST",
        path: "/device/update/",
        data: deviceData,
      });
      if (data.status === "OK") {
        setDevices((prev) =>
          prev.map((d) =>
            d.device_id === deviceData.device_id ? { ...d, ...deviceData } : d
          )
        );
        showNotification({
          title: "Device Updated",
          message: "The device has been successfully updated.",
          color: "green",
        });
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Update Device Failed",
        message: error.message,
        color: "red",
      });
    }
  };

  const deleteDevice = async (deviceId: string) => {
    try {
      const data = await apiCall({
        method: "GET",
        path: `/device/delete/?device_id=${deviceId}`,
      });
      if (data.status === "OK") {
        setDevices((prev) => prev.filter((d) => d.device_id !== deviceId));
        showNotification({
          title: "Device Deleted",
          message: "The device has been successfully deleted.",
          color: "green",
        });
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Delete Device Failed",
        message: error.message,
        color: "red",
      });
    }
  };

  return (
    <DeviceContext.Provider
      value={{
        devices,
        setDevices,
        fetchDevices,
        addDevice,
        updateDevice,
        deleteDevice,
      }}
    >
      {children}
    </DeviceContext.Provider>
  );
};

export const useDevices = () => {
  const context = useContext(DeviceContext);
  if (context === undefined) {
    throw new Error("useDevices must be used within a DeviceProvider");
  }
  return context;
};
