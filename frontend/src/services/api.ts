import axios from "axios";
import type {AxiosInstance} from "axios";

const api: AxiosInstance = axios.create({
  // baseURL: "https://whatodo.azurewebsites.net/api"
  baseURL: "http://localhost:8000/api"
});

export default api;
