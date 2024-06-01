"use client";

import React, {
  createContext,
  useContext,
  useState,
  ReactNode,
  useEffect,
} from "react";
import { apiCall } from "../utils/apiCall";
import { showNotification } from "@mantine/notifications";
import { useUser } from "./UserContext";

interface Device {
  serial_number: string;
  device_id: string;
  name: string;
  location: string;
  co2_green: number;
  co2_yellow: number;
  co2_red: number;
}

interface SensorData {
  // Define the structure of sensor data as per your API response
}

interface DeviceContextType {
  isLoadingDevices: boolean;
  devices: Device[];
  setDevices: React.Dispatch<React.SetStateAction<Device[]>>;
  fetchDevices: () => Promise<void>;
  addDevice: (deviceData: Device) => Promise<void>;
  updateDevice: (deviceData: Device) => Promise<void>;
  deleteDevice: (deviceId: string) => Promise<void>;
  getSensorData: (deviceId: string, date: string) => Promise<SensorData[]>;
  getDeviceState: (deviceId: string) => Promise<boolean>;
  getAllDeviceStates: () => Promise<{ device_id: string, is_online: boolean }[]>;
}

const DeviceContext = createContext<DeviceContextType | undefined>(undefined);

export const DeviceProvider = ({ children }: { children: ReactNode }) => {
  const [devices, setDevices] = useState<Device[]>([]);
  const { isLoading, user } = useUser();
  const [isLoadingDevices, setIsLoadingDevices] = useState(true);

  useEffect(() => {
    if (!isLoading && user) {
      // If user is loaded and not loading
      fetchDevices();
    }
  }, [isLoading]);

  const fetchDevices = async () => {
    setIsLoadingDevices(true);
    try {
      const data = await apiCall({
        method: "GET",
        path: "/device/get-list/",
      })

      if (data.status === "OK") {
        let devices = [];
        for (var key in data.data) {
          devices.push(data.data[key]);
        }
        setDevices(devices);
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Loading Failed",
        message: error.message,
        color: "red",
      });
    }
    setTimeout(() => {
      // Simulate loading time
      setIsLoadingDevices(false);
    }, 1000);
  };

  const addDevice = async (deviceData: Device) => {
    setIsLoadingDevices(true);

    try {
      const data = await apiCall({
        method: "POST",
        path: "/device/add/",
        data: deviceData,
      });

      if (data.status === "OK") {
        setDevices((prev) => [...prev, data.data]);
        showNotification({
          title: "Device Added",
          message: data.error,
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
    setTimeout(() => {
      // Simulate loading time
      setIsLoadingDevices(false);
    }, 1000);
  };

  const updateDevice = async (deviceData: Device) => {
    setIsLoadingDevices(true);

    try {
      const data = await apiCall({
        method: "POST",
        path: "/device/update/",
        data: deviceData,
      }); // 5-second timeout

      if (data.status === "OK") {
        setDevices((prev) =>
          prev.map((d) =>
            d.device_id === deviceData.device_id ? { ...d, ...deviceData } : d
          )
        );
        showNotification({
          title: "Device Updated",
          message: data.error,
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
    setTimeout(() => {
      // Simulate loading time
      setIsLoadingDevices(false);
    }, 1000);
  };

  const deleteDevice = async (deviceId: string) => {
    setIsLoadingDevices(true);

    try {
      const data = await apiCall({
        method: "GET",
        path: `/device/delete/?device_id=${deviceId}`,
      }); // 5-second timeout

      if (data.status === "OK") {
        setDevices((prev) => prev.filter((d) => d.device_id !== deviceId));
        showNotification({
          title: "Device Deleted",
          message: data.error,
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
    setTimeout(() => {
      // Simulate loading time
      setIsLoadingDevices(false);
    }, 1000);
  };

  const getSensorData = async (deviceId: string, date: string) => {
    setIsLoadingDevices(true);
    try {
      const data = await apiCall({
        method: "GET",
        path: `/data/get-data/?device_id=${deviceId}&date=${date}`,
      }); // 5-second timeout

      if (data.status === "OK") {
        return data.sensors_datas;
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Get Sensor Data Failed",
        message: error.message,
        color: "red",
      });
      return [];
    } finally {
      setIsLoadingDevices(false);
    }
  };

  const getDeviceState = async (deviceId: string) => {
    setIsLoadingDevices(true);
    try {
      const data = await apiCall({
        method: "GET",
        path: `/data/get-state/?device_id=${deviceId}`,
      }); // 5-second timeout

      if (data.status === "OK") {
        return data.is_online;
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Get Device State Failed",
        message: error.message,
        color: "red",
      });
      return false;
    } finally {
      setIsLoadingDevices(false);
    }
  };

  const getAllDeviceStates = async () => {
    setIsLoadingDevices(true);
    try {
      const data = await apiCall({
        method: "GET",
        path: `/data/get-all-states/`,
      }); // 5-second timeout

      if (data.status === "OK") {
        return data.states;
      } else {
        throw new Error(data.error);
      }
    } catch (error: any) {
      showNotification({
        title: "Get All Device States Failed",
        message: error.message,
        color: "red",
      });
      return [];
    } finally {
      setIsLoadingDevices(false);
    }
  };

  return (
    <DeviceContext.Provider
      value={{
        isLoadingDevices,
        devices,
        setDevices,
        fetchDevices,
        addDevice,
        updateDevice,
        deleteDevice,
        getSensorData,
        getDeviceState,
        getAllDeviceStates,
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
